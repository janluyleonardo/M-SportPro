<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->locale('es')->dayName; // Obtener nombre del día en español
        $today = ucfirst($today);

        // Los profesores solo ven sus clases de hoy
        // Los Admin ven todas las clases de hoy
        $query = ClassSchedule::with('teacher')
            ->where('day_of_week', $today);

        if (Auth::user()->hasRole('Profesor')) {
            $query->where('user_id', Auth::id());
        }

        $schedules = $query->orderBy('start_time')->get();

        return view('attendances.index', compact('schedules'));
    }

    public function show(ClassSchedule $schedule)
    {
        // Buscar estudiantes que pertenecen a la categoría de esta clase
        $students = Student::where('Categoria', $schedule->category)->orderBy('nomDeportista')->get();
        
        // Sincronizar saldos antes de mostrar la lista
        $students->each->updateBalance();
        
        // Verificar si ya se tomó asistencia hoy para esta clase
        $existingAttendances = Attendance::where('class_schedule_id', $schedule->id)
            ->where('date', now()->format('Y-m-d'))
            ->get()
            ->pluck('status', 'student_id');

        return view('attendances.show', compact('schedule', 'students', 'existingAttendances'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();

        $date = now()->format('Y-m-d');
        $month = now()->month;
        $year = now()->year;

        foreach ($validated['students'] as $studentId => $status) {
            // 1. Registrar la asistencia
            Attendance::updateOrCreate(
                [
                    'class_schedule_id' => $request->class_schedule_id,
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                ['status' => $status]
            );

            // 2. Si asistió, descontar de la mensualidad (si existe pago)
            if ($status === 'present') {
                $payment = Payment::where('student_id', $studentId)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                if ($payment) {
                    $payment->increment('classes_used');
                }
            }
        }

        return redirect()->route('attendances.index')->with('success', 'Asistencia registrada correctamente.');
    }
}
