<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClassScheduleRequest;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());
        $carbonDate = \Carbon\Carbon::parse($selectedDate);
        $startOfWeek = $carbonDate->copy()->startOfWeek();
        $endOfWeek = $carbonDate->copy()->endOfWeek();

        $schedules = ClassSchedule::with('teacher')
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->withExists(['attendances' => function($query) {
                $query->whereColumn('attendances.date', 'class_schedules.date');
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $teachers  = User::role('Profesor')->get();
        $locations = Location::active()->orderBy('name')->get();
        
        return view('schedules.index', compact(
            'schedules', 
            'teachers', 
            'locations', 
            'startOfWeek', 
            'endOfWeek', 
            'selectedDate'
        ));
    }

    public function store(StoreClassScheduleRequest $request)
    {
        $validated = $request->validated();
        
        // Auto-set day_of_week based on date
        $date = \Carbon\Carbon::parse($validated['date']);
        $dayMap = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];
        $validated['day_of_week'] = $dayMap[$date->dayOfWeek];

        ClassSchedule::create($validated);

        return redirect()->route('schedules.index', ['date' => $validated['date']])
            ->with('success', 'Clase programada correctamente.');
    }

    public function update(StoreClassScheduleRequest $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validated();
        
        // Auto-set day_of_week based on date
        $date = \Carbon\Carbon::parse($validated['date']);
        $dayMap = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];
        $validated['day_of_week'] = $dayMap[$date->dayOfWeek];

        $classSchedule->update($validated);

        return redirect()->route('schedules.index', ['date' => $validated['date']])
            ->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Horario eliminado.');
    }
}
