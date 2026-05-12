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
        $user = auth()->user();

        // Seguridad y UX: Si no es Admin ni Profesor, manejar redirección directa
        if (!$user->hasRole(['Admin', 'Profesor'])) {
            $doc = $user->documento_deportista;
            
            if (!$doc) {
                return view('payments.index', [
                    'students' => Student::where('id', 0)->paginate(6), 
                    'search' => $search,
                    'error_message' => 'Tu cuenta no tiene un número de documento vinculado. Contacta al administrador.'
                ]);
            }

            $student = Student::where('numDocumento', $doc)->first();

            if (!$student) {
                return view('payments.index', [
                    'students' => Student::where('id', 0)->paginate(6), 
                    'search' => $search,
                    'error_message' => "No se encontró ningún deportista con el documento $doc. Verifica la información con el club."
                ]);
            }

            // Si existe, lo llevamos directo a su línea de tiempo
            return redirect()->route('payments.show', $student->id);
        }

        $query = Student::query();
        // (El resto de la lógica para Admin/Profesor sigue igual...)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomDeportista', 'LIKE', "%$search%")
                  ->orWhere('numDocumento', 'LIKE', "%$search%");
            });
        }

        $students = $query->orderBy('nomDeportista', 'asc')->paginate(6);
        $students->getCollection()->each->updateBalance();

        return view('payments.index', compact('students', 'search'));
    }

    public function show(Student $student)
    {
        $user = auth()->user();

        // Seguridad: Si no es Admin ni Profesor, verificar que sea su registro vinculado
        if (!$user->hasRole(['Admin', 'Profesor'])) {
            if ((string)$user->documento_deportista !== (string)$student->numDocumento) {
                abort(403, 'No tienes permiso para ver esta información.');
            }
        }

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

        $payment = Payment::create($validated);
        
        // Registrar ingreso en Tesorería
        \App\Models\Transaction::create([
            'type' => 'income',
            'category' => 'monthly_payment',
            'amount' => $payment->amount,
            'date' => now()->format('Y-m-d'),
            'description' => "Pago manual mensualidad {$payment->month}/{$payment->year} - {$payment->student->nomDeportista}",
            'student_id' => $payment->student_id,
            'reference_id' => $payment->id,
        ]);

        $student = Student::find($validated['student_id']);
        $student->updateBalance();

        return redirect()->route('payments.show', $validated['student_id'])
            ->with('success', 'Pago registrado y contabilizado en tesorería.');
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

    public function uploadVoucher(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'amount' => 'required|numeric',
            'voucher' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('voucher');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('vouchers'), $filename);

        Payment::create([
            'student_id' => $request->student_id,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
            'status' => 'pending',
            'voucher' => 'vouchers/' . $filename,
            'voucher_status' => 'pending',
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'paid_at' => null,
            'classes_available' => 8,
            'classes_used' => 0,
        ]);

        return back()->with('success', 'Comprobante subido correctamente. Pendiente de verificación por el administrador.');
    }

    public function verifyVoucher(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'voucher_status' => 'approved',
            'paid_at' => now(),
        ]);

        // Registrar ingreso en Tesorería
        \App\Models\Transaction::create([
            'type' => 'income',
            'category' => 'monthly_payment',
            'amount' => $payment->amount,
            'date' => now()->format('Y-m-d'),
            'description' => "Mensualidad {$payment->month}/{$payment->year} - {$payment->student->nomDeportista}",
            'student_id' => $payment->student_id,
            'reference_id' => $payment->id,
        ]);

        $payment->student->updateBalance();

        return back()->with('success', 'Pago verificado y registrado en tesorería.');
    }

    public function rejectVoucher(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->update([
            'voucher_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('warning', 'Comprobante rechazado.');
    }
}
