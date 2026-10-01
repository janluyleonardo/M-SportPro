<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Location;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClassScheduleRequest;
use App\Http\Requests\MergeClassSchedulesRequest;
use App\Services\CustomLogger;
use Illuminate\Support\Facades\DB;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());
        $carbonDate = \Carbon\Carbon::parse($selectedDate);
        $startOfWeek = $carbonDate->copy()->startOfWeek();
        $endOfWeek = $carbonDate->copy()->endOfWeek();

        $schedules = ClassSchedule::with('teacher')
            ->where('active', true)
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->withExists(['attendances' => function($query) {
                $query->whereColumn('attendances.date', 'class_schedules.date');
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        if (auth()->user()->is_super_admin) {
            $teachers = User::role('Profesor')->get();
        } else {
            $teachers = User::role('Profesor')->where('club_id', auth()->user()->club_id)->get();
        }

        $locations = Location::active()->orderBy('name')->get();
        $clubs = auth()->user()->is_super_admin ? \App\Models\Club::all() : collect();

        $categoriesQuery = Student::query()
            ->whereNotNull('Categoria')
            ->where('Categoria', '!=', '');
        if (!auth()->user()->is_super_admin) {
            $categoriesQuery->where('club_id', auth()->user()->club_id);
        }
        $categories = $categoriesQuery
            ->distinct()
            ->orderBy('Categoria')
            ->pluck('Categoria');

        return view('schedules.index', compact(
            'schedules',
            'teachers',
            'locations',
            'startOfWeek',
            'endOfWeek',
            'selectedDate',
            'clubs',
            'categories'
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

        try {
            $schedule = ClassSchedule::create($validated);
            $schedule->categories()->create(['category' => $validated['category']]);

            return redirect()->route('schedules.index', ['date' => $validated['date']])
                ->with('success', 'Clase programada correctamente.');
        } catch (\Throwable $th) {
            CustomLogger::logException($th);
            return back()->withInput()->with('error', 'No se pudo programar la clase: ' . $th->getMessage());
        }
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

        try {
            $classSchedule->update($validated);

            if ($classSchedule->categories()->count() <= 1) {
                $classSchedule->categories()->delete();
                $classSchedule->categories()->create(['category' => $validated['category']]);
            }

            return redirect()->route('schedules.index', ['date' => $validated['date']])
                ->with('success', 'Horario actualizado correctamente.');
        } catch (\Throwable $th) {
            CustomLogger::logException($th);
            return back()->withInput()->with('error', 'No se pudo actualizar el horario: ' . $th->getMessage());
        }
    }

    public function merge(MergeClassSchedulesRequest $request)
    {
        $validated = $request->validated();
        $schedules = ClassSchedule::with('categories')
            ->whereIn('id', $validated['schedule_ids'])
            ->where('active', true)
            ->get();

        if ($schedules->count() !== count($validated['schedule_ids'])) {
            return back()->withInput()->with('error', 'Una o más clases seleccionadas ya no están activas.');
        }

        $first = $schedules->first();
        $sameSlot = $schedules->every(function ($schedule) use ($first) {
            return $schedule->club_id === $first->club_id
                && $schedule->date->toDateString() === $first->date->toDateString()
                && $schedule->start_time === $first->start_time
                && $schedule->end_time === $first->end_time;
        });

        if (!$sameSlot) {
            return back()->withInput()->with('error', 'Solo puedes unir clases del mismo club, fecha y horario.');
        }

        $categories = $schedules->flatMap(function ($schedule) {
            return $schedule->categories->isNotEmpty()
                ? $schedule->categories->pluck('category')
                : collect([$schedule->category]);
        })->unique()->values();

        try {
            DB::transaction(function () use ($first, $schedules, $validated, $categories) {
                $merged = ClassSchedule::create([
                    'club_id' => $first->club_id,
                    'day_of_week' => $first->day_of_week,
                    'date' => $first->date,
                    'start_time' => $first->start_time,
                    'end_time' => $first->end_time,
                    'category' => $categories->implode(' + '),
                    'user_id' => $validated['user_id'],
                    'location' => $validated['location'] ?? null,
                    'observations' => $validated['observations'],
                    'active' => true,
                ]);

                $merged->categories()->createMany($categories->map(fn ($category) => ['category' => $category])->all());
                ClassSchedule::whereIn('id', $schedules->pluck('id'))->update(['active' => false]);
            });

            return redirect()->route('schedules.index', ['date' => $first->date])
                ->with('success', 'Clases unidas correctamente. Las programaciones originales quedaron desactivadas.');
        } catch (\Throwable $th) {
            CustomLogger::logException($th);
            return back()->withInput()->with('error', 'No se pudieron unir las clases: ' . $th->getMessage());
        }
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        try {
            $classSchedule->delete();
            return redirect()->route('schedules.index')->with('success', 'Horario eliminado.');
        } catch (\Throwable $th) {
            CustomLogger::logException($th);
            return redirect()->route('schedules.index')->with('error', 'No se pudo eliminar el horario: ' . $th->getMessage());
        }
    }
}
