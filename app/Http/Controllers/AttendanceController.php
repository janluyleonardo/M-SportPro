<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\AttendanceOverride;
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
            ->whereDate('date', now()->toDateString());

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
            ->where('date', $schedule->date)
            ->get()
            ->pluck('status', 'student_id');

        // Cargar overrides activos para esta clase (indexados por student_id)
        $overrides = AttendanceOverride::where('class_schedule_id', $schedule->id)
            ->with('authorizedBy')
            ->get()
            ->keyBy('student_id');

        return view('attendances.show', compact('schedule', 'students', 'existingAttendances', 'overrides'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();
        $schedule = ClassSchedule::findOrFail($request->class_schedule_id);

        $date = $schedule->date;
        $month = \Carbon\Carbon::parse($date)->month;
        $year = \Carbon\Carbon::parse($date)->year;

        foreach ($validated['students'] as $studentId => $status) {
            // 1. Verificar si ya existía un registro para este alumno hoy en esta clase
            $existingAttendance = Attendance::where([
                'class_schedule_id' => $request->class_schedule_id,
                'student_id' => $studentId,
                'date' => $date,
            ])->first();

            $oldStatus = $existingAttendance ? $existingAttendance->status : null;

            // 2. Registrar o actualizar la asistencia
            Attendance::updateOrCreate(
                [
                    'class_schedule_id' => $request->class_schedule_id,
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                ['status' => $status]
            );

            // 3. Lógica de incremento/decremento de clases usadas (Solo si el estado cambió)
            if ($status !== $oldStatus) {
                // Buscamos o creamos el cupo de asistencia para este mes/año
                // Nota: El cupo se asocia al estudiante y al mes/año, no a un pago específico
                $slot = \App\Models\AttendanceSlot::firstOrCreate(
                    [
                        'student_id' => $studentId,
                        'month' => $month,
                        'year' => $year
                    ],
                    [
                        'classes_used' => 0,
                        'classes_allowed' => 8
                    ]
                );

                if ($status === 'present' && $oldStatus !== 'present') {
                    // Cambió de Ausente a Presente: Sumar clase
                    $slot->increment('classes_used');
                } elseif ($status !== 'present' && $oldStatus === 'present') {
                    // Cambió de Presente a Ausente (Corrección): Restar clase
                    if ($slot->classes_used > 0) {
                        $slot->decrement('classes_used');
                    }
                }
            }
        }

        return redirect()->route('attendances.index')->with('success', 'Asistencia registrada correctamente.');
    }

    /**
     * Admin habilita manualmente a un estudiante bloqueado para una clase específica.
     */
    public function storeOverride(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:students,id',
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'reason'            => 'nullable|string|max:500',
        ]);

        AttendanceOverride::updateOrCreate(
            [
                'student_id'        => $request->student_id,
                'class_schedule_id' => $request->class_schedule_id,
            ],
            [
                'authorized_by' => Auth::id(),
                'reason'        => $request->reason,
            ]
        );

        $schedule = ClassSchedule::findOrFail($request->class_schedule_id);

        return redirect()
            ->route('attendances.show', $schedule)
            ->with('success', 'Estudiante habilitado para esta clase.');
    }

    /**
     * Admin revoca la habilitación de un estudiante.
     */
    public function destroyOverride(AttendanceOverride $override)
    {
        $schedule = $override->classSchedule;
        $override->delete();

        return redirect()
            ->route('attendances.show', $schedule)
            ->with('success', 'Habilitación revocada.');
    }
}

