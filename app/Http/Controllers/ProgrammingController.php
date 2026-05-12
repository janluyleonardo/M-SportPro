<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProgrammingRequest;
use App\Models\Student;
use App\Models\programming;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProgrammingController extends Controller
{
    public function imprimir($date)
    {
        $programming = programming::where('fecha', $date)->orderBy('hora')->get();
        
        if ($programming->isEmpty()) {
            return back()->with('error', 'No hay programación para esta fecha.');
        }

        $studentNames = Student::pluck('nomDeportista', 'id')->toArray();

        $pdf = Pdf::loadView('Programming.pdf', compact('programming', 'date', 'studentNames'));
        return $pdf->stream('Programacion_'.$date.'.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $studentList = Student::select('id', 'nomDeportista', 'Categoria')
      ->orderByDesc('id')
      ->get();
      $texto = trim($request->get('texto'));
      
      // Get all programming records ordered by date and time for the Calendar
      $programming = programming::orderBy('fecha')->orderBy('hora')->get();
      
      // Group by date for easier parsing in frontend
      $eventsByDate = $programming->groupBy('fecha')->map(function($items) {
          return $items->toArray();
      })->toJson();

      // Get tournaments for selection with associated students
      $tournaments = \App\Models\Tournament::with(['students' => function($q) {
          $q->select('students.id');
      }])->where('status', 'activo')->orderBy('name')->get();

      return view('Programming.index', compact('texto','programming','studentList', 'eventsByDate', 'tournaments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgrammingRequest $request)
    {
      $validated = $request->validated();
      // Si hay un torneo seleccionado, cargar automáticamente a todos sus deportistas asociados
      if (!empty($validated['tournament_id'])) {
          $tournament = \App\Models\Tournament::with('students')->find($validated['tournament_id']);
          if ($tournament) {
              $validated['jugadores_convocados'] = $tournament->students->pluck('id')->toArray();
          }
      }

      // Manejar la conversión de array a string para la DB
      if (isset($validated['jugadores_convocados']) && is_array($validated['jugadores_convocados'])) {
          $validated['jugadores_convocados'] = implode(',', $validated['jugadores_convocados']);
      } else {
          $validated['jugadores_convocados'] = $validated['jugadores_convocados'] ?? '';
      }

      try {
        $conflict = $this->checkConflict($validated['fecha'], $validated['hora'], $validated['cancha']);
        if ($conflict) {
            return back()->withInput()->with('error', "Conflicto de horario: Ya existe un partido programado a las $conflict->hora en la cancha $conflict->cancha. Los partidos duran 1 hora.");
        }

        programming::create($validated);
        return redirect()->route('programming.index')->with('success', 'Registro creado correctamente.');
      } catch (\Throwable $th) {
        return back()->withInput()->with('error', 'No se pudo crear nuevo registro => '.$th->getMessage());
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, programming $programming)
    {
      return view('Programming.index', compact('programming'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProgrammingRequest $request, $id)
    {
      $programming = programming::findOrFail($id);
      $validated = $request->validated();

      // Si hay un torneo seleccionado, cargar automáticamente a todos sus deportistas asociados
      if (!empty($validated['tournament_id'])) {
          $tournament = \App\Models\Tournament::with('students')->find($validated['tournament_id']);
          if ($tournament) {
              $validated['jugadores_convocados'] = $tournament->students->pluck('id')->toArray();
          }
      }

      if (isset($validated['jugadores_convocados']) && is_array($validated['jugadores_convocados'])) {
          $validated['jugadores_convocados'] = implode(',', $validated['jugadores_convocados']);
      }

      try {
        $conflict = $this->checkConflict($validated['fecha'], $validated['hora'], $validated['cancha'], $id);
        if ($conflict) {
            return back()->withInput()->with('error', "Conflicto de horario: Ya existe un partido programado a las $conflict->hora en la cancha $conflict->cancha.");
        }

        $programming->update($validated);
        return redirect()->route('programming.index')->with('success', 'Registro actualizado correctamente.');
      } catch (\Throwable $th) {
        return back()->withInput()->with('error', 'No se pudo actualizar registro porque => '.$th->getMessage());
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $programming = programming::findOrFail($id);
      try {
        $programming->delete();
        return redirect()->route('programming.index')->with('success', 'Registro eliminado correctamente.');
      } catch (\Throwable $th) {
        return redirect()->route('programming.index')->with('error', 'No se pudo eliminar registro porque => '.$th->getMessage());
      }
    }

    public function getPayments($id)
    {
        $programming = programming::findOrFail($id);
        $ids = explode(',', $programming->jugadores_convocados);
        
        $students = Student::whereIn('id', $ids)->select('id', 'nomDeportista')->get();
        $payments = \App\Models\ProgrammingPayment::where('programming_id', $id)->get()->keyBy('student_id');

        $data = $students->map(function($student) use ($payments) {
            $payment = $payments->get($student->id);
            return [
                'student_id' => $student->id,
                'name' => $student->nomDeportista,
                'pagado_inscripcion' => $payment ? (bool)$payment->pagado_inscripcion : false,
                'pagado_arbitraje' => $payment ? (bool)$payment->pagado_arbitraje : false,
            ];
        });

        return response()->json($data);
    }

    public function updatePayments(Request $request, $id)
    {
        $payments = $request->input('payments', []);

        foreach ($payments as $p) {
            \App\Models\ProgrammingPayment::updateOrCreate(
                ['programming_id' => $id, 'student_id' => $p['student_id']],
                [
                    'pagado_inscripcion' => $p['pagado_inscripcion'] ?? false,
                    'pagado_arbitraje' => $p['pagado_arbitraje'] ?? false,
                    'fecha_pago' => ($p['pagado_inscripcion'] || $p['pagado_arbitraje']) ? now() : null,
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    private function checkConflict($date, $time, $court, $excludeId = null)
    {
        try {
            $newStart = Carbon::parse("$date $time");
            $newEnd = (clone $newStart)->addMinutes(59); // Consideramos 1 hora de duración (59 min para evitar borde exacto)

            $conflicts = programming::where('fecha', $date)
                ->where('cancha', $court)
                ->when($excludeId, function($q) use ($excludeId) {
                    $q->where('id', '!=', $excludeId);
                })
                ->get();

            foreach ($conflicts as $conflict) {
                $existingStart = Carbon::parse("$conflict->fecha $conflict->hora");
                $existingEnd = (clone $existingStart)->addMinutes(59);

                // Traslape: (InicioA <= FinB) y (FinA >= InicioB)
                if ($newStart <= $existingEnd && $newEnd >= $existingStart) {
                    return $conflict;
                }
            }
        } catch (\Throwable $th) {
            // Si falla el parseo de fecha, ignoramos el conflicto para no bloquear el flujo principal
        }

        return null;
    }
}
