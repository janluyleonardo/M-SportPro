<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::with(['students'])->withCount('programmings')->orderByDesc('created_at')->get();
        $studentList = \App\Models\Student::select('id', 'nomDeportista', 'Categoria')->orderBy('nomDeportista')->get();
        return view('Tournaments.index', compact('tournaments', 'studentList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'costo_total_inscripcion' => 'nullable|numeric|min:0',
            'costo_total_arbitraje' => 'nullable|numeric|min:0',
        ]);

        $tournament = Tournament::create($validated);

        if ($request->has('student_ids')) {
            $tournament->students()->sync($request->input('student_ids'));
        }

        return redirect()->route('tournaments.index')->with('success', 'Torneo creado correctamente.');
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'status' => 'required|in:activo,finalizado',
            'costo_total_inscripcion' => 'nullable|numeric|min:0',
            'costo_total_arbitraje' => 'nullable|numeric|min:0',
        ]);

        $tournament->update($validated);

        if ($request->has('student_ids')) {
            $tournament->students()->sync($request->input('student_ids'));
        }

        return redirect()->route('tournaments.index')->with('success', 'Torneo actualizado correctamente.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('tournaments.index')->with('success', 'Torneo eliminado correctamente.');
    }

    public function payments(Tournament $tournament)
    {
        $programmings = $tournament->programmings()
            ->withTrashed()
            ->with(['payments.student'])
            ->orderBy('fecha') // Ordenar por fecha ascendente para calcular deuda acumulada correctamente
            ->get();

        // Mapa para llevar el seguimiento de la deuda acumulada por estudiante
        $debtTracker = [];
        
        // Inicializar deuda para todos los estudiantes del torneo
        foreach ($tournament->students as $student) {
            $debtTracker[$student->id] = 0;
        }

        $programmings->each(function($prog) use (&$debtTracker) {
            $ids = array_filter(explode(',', $prog->jugadores_convocados));
            if (!empty($ids)) {
                $students = \App\Models\Student::whereIn('id', $ids)->select('id', 'nomDeportista')->get();
                $existingPayments = $prog->payments->keyBy('student_id');
                
                $prog->summoned_data = $students->map(function($student) use ($existingPayments, $prog, &$debtTracker) {
                    $payment = $existingPayments->get($student->id);
                    
                    // Deuda que traía antes de este partido
                    $previous_debt = $debtTracker[$student->id] ?? 0;
                    
                    $pagado_ins = $payment ? (float)$payment->pagado_inscripcion : 0;
                    $pagado_arb = $payment ? (float)$payment->pagado_arbitraje : 0;
                    
                    // Costo total de este partido
                    $cost_this_match = (float)$prog->costo_inscripcion + (float)$prog->costo_arbitraje;
                    
                    // Actualizar el rastreador de deuda para el siguiente partido
                    // Nueva Deuda = Deuda Anterior + Costo Match - Pagado Match
                    $new_debt = $previous_debt + $cost_this_match - ($pagado_ins + $pagado_arb);
                    $debtTracker[$student->id] = $new_debt;

                    return [
                        'student_id' => $student->id,
                        'name' => $student->nomDeportista,
                        'pagado_inscripcion' => $pagado_ins,
                        'pagado_arbitraje' => $pagado_arb,
                        'previous_debt' => $previous_debt,
                        'has_payment' => $payment ? true : false
                    ];
                });
            } else {
                $prog->summoned_data = collect();
            }
        });
            
        // Volvemos a ordenar por fecha descendente para la vista (más reciente arriba)
        $programmings = $programmings->sortByDesc('fecha')->values();

        return view('Tournaments.payments', compact('tournament', 'programmings'));
    }
}
