<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClassScheduleRequest;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with('teacher')
            ->withExists(['attendances' => function($query) {
                $query->whereDate('date', now()->toDateString());
            }])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $teachers  = User::role('Profesor')->get();
        $locations = Location::active()->orderBy('name')->get();
        return view('schedules.index', compact('schedules', 'teachers', 'locations'));
    }

    public function store(StoreClassScheduleRequest $request)
    {
        $validated = $request->validated();

        ClassSchedule::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Clase programada correctamente.');
    }

    public function update(StoreClassScheduleRequest $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validated();

        $classSchedule->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Horario eliminado.');
    }
}
