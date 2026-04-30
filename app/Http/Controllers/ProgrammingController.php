<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $pdf = Pdf::loadView('Programming.pdf', compact('programming', 'date'));
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

      return view('Programming.index', compact('texto','programming','studentList', 'eventsByDate'));
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
    public function store(Request $request, programming $programming)
    {
      $newProgramming = new Programming();
      $newProgramming->torneo = $request->torneo;
      $newProgramming->cancha = $request->cancha;
      $newProgramming->categoriaUno = $request->categoriaUno;
      $newProgramming->categoriaDos = $request->categoriaDos ?? '';
      $newProgramming->eLocal = $request->eLocal;
      $newProgramming->eVisitante = $request->eVisitante;
      $newProgramming->hora = $request->hora;
      $newProgramming->fecha = $request->fecha;
      $newProgramming->jugadores_convocados = implode(',', $request->jugadores_convocados ?? []);

      try {
        $newProgramming->save();
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
    public function update(Request $request, $id)
    {
      $programming = programming::findOrFail($id);
      try {
        $data = $request->all();
        $data['categoriaDos'] = $data['categoriaDos'] ?? '';
        $programming->update($data);
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
}
