<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
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
        
        // Actualizar saldos dinámicamente para los estudiantes visibles
        $students->getCollection()->each->updateBalance();

        return view('payments.index', compact('students', 'search'));
    }

    public function show(Student $student)
    {
        $payments = $student->payments()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $student->updateBalance();
        $monthStatuses = $student->getPaymentStatusByMonth();
        return view('payments.show', compact('student', 'payments', 'monthStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $paidAt = $request->paid_at ? \Carbon\Carbon::parse($request->paid_at) : now();
        $dayThreshold = config('app.payment_late_day_threshold', 10);
        $feePercentage = config('app.payment_late_fee_percentage', 10);
        $extraMessage = '';

        // Determinar si es pago tardío basado en mes/año y día
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $selectedMonth = (int)$validated['month'];
        $selectedYear = (int)$validated['year'];

        $isLate = false;
        if ($selectedYear < $currentYear) {
            $isLate = true;
        } elseif ($selectedYear == $currentYear) {
            if ($selectedMonth < $currentMonth) {
                $isLate = true;
            } elseif ($selectedMonth == $currentMonth && $paidAt->day > $dayThreshold) {
                $isLate = true;
            }
        }

        // Aplicar recargo si es tarde
        if ($isLate) {
            $extra = $validated['amount'] * ($feePercentage / 100);
            $validated['amount'] += $extra;
            $extraMessage = " (Se aplicó un recargo del {$feePercentage}% por pago extemporáneo)";
        }

        $validated['status'] = 'paid';
        $validated['paid_at'] = $paidAt;

        Payment::create($validated);
        
        $student = Student::find($validated['student_id']);
        $student->updateBalance();

        return redirect()->route('payments.show', $validated['student_id'])
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

        $payment->student->updateBalance();

        return back()->with('success', $msg ?? 'Registro actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $studentId = $payment->student_id;
        $payment->delete();
        Student::find($studentId)->updateBalance();
        return back()->with('success', 'Pago eliminado correctamente.');
    }
}
