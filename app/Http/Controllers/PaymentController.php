<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\AttendanceSlot;

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
        $payments = $student->payments()->with('user')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $student->updateBalance();
        $monthStatuses = $student->getPaymentStatusByMonth();
        $attendanceSlots = $student->attendanceSlots;
        return view('payments.show', compact('student', 'payments', 'monthStatuses', 'attendanceSlots'));
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
        
        // Si viene una fecha manual, la parseamos y le ponemos la hora actual
        // Si no viene fecha, usamos el momento exacto (ahora)
        $paidAt = $request->paid_at 
            ? \Carbon\Carbon::parse($request->paid_at)->setTimeFrom(now()) 
            : now();

        $validated['status'] = 'paid';
        $validated['paid_at'] = $paidAt;
        $validated['user_id'] = auth()->id();

        Payment::create($validated);
        
        $student = Student::find($validated['student_id']);
        $student->updateBalance();

        return redirect()->route('payments.show', $validated['student_id'])
            ->with('success', 'Pago registrado correctamente.');
    }
    public function update(Request $request, $id)
    {
        if ($request->has('increment_classes')) {
            $slot = AttendanceSlot::findOrFail($id);
            if ($slot->classes_used < $slot->classes_allowed) {
                $slot->increment('classes_used');
                return back()->with('success', 'Asistencia registrada correctamente.');
            }
            return back()->with('error', 'El estudiante ya cumplió sus clases de este mes.');
        }

        return back();
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
