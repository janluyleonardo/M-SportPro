<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $students = Student::when($search, function($query) use ($search) {
                $query->where('nomDeportista', 'LIKE', "%$search%")
                      ->orWhere('numDocumento', 'LIKE', "%$search%");
            })
            ->orderBy('nomDeportista', 'asc')
            ->paginate(6);

        return view('payments.index', compact('students', 'search'));
    }

    public function show(Student $student)
    {
        $payments = $student->payments()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('payments.show', compact('student', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $paidAt = $request->paid_at ? \Carbon\Carbon::parse($request->paid_at) : now();
        $dayThreshold = env('PAYMENT_LATE_DAY_THRESHOLD', 10);
        $feePercentage = env('PAYMENT_LATE_FEE_PERCENTAGE', 10);
        $extraMessage = '';

        // Regla de Negocio: Recargo por pago tardío
        if ($paidAt->day > $dayThreshold) {
            $extra = $validated['amount'] * ($feePercentage / 100);
            $validated['amount'] += $extra;
            $extraMessage = " (Se aplicó un recargo del {$feePercentage}% por pago tardío)";
        }

        $validated['status'] = 'paid';
        $validated['paid_at'] = $paidAt;

        // Evitar duplicados
        $exists = Payment::where('student_id', $request->student_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Este estudiante ya tiene un pago registrado para este mes.');
        }

        Payment::create($validated);

        return redirect()->route('payments.show', $request->student_id)
            ->with('success', 'Pago registrado correctamente.' . $extraMessage);
    }
    public function update(Request $request, Payment $payment)
    {
        if ($request->has('status')) {
            $payment->update([
                'status' => $request->status,
                'paid_at' => $request->status == 'paid' ? now() : null
            ]);
            $msg = 'Estado de pago actualizado.';
        }

        if ($request->has('increment_classes')) {
            if ($payment->classes_used < $payment->classes_available) {
                $payment->increment('classes_used');
                $msg = 'Asistencia registrada correctamente.';
            } else {
                return back()->with('error', 'El estudiante ya cumplió sus 8 clases de este mes.');
            }
        }

        return back()->with('success', $msg ?? 'Registro actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Pago eliminado correctamente.');
    }
}
