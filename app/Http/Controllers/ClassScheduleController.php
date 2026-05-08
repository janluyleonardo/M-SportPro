<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with('teacher')->orderBy('day_of_week')->orderBy('start_time')->get();
        $teachers  = User::role('Profesor')->get();
        $locations = Location::active()->orderBy('name')->get();
        return view('schedules.index', compact('schedules', 'teachers', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        ClassSchedule::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Clase programada correctamente.');
    }

    public function update(Request $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        $classSchedule->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Horario eliminado.');
    }
}
