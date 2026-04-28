<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\programming;
use Illuminate\Support\Facades\DB;

class ProgrammingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $studentList = Student::select('nomDeportista')
      ->orderByDesc('id')
      ->get();
      $texto = trim($request->get('texto'));
      // $students = Student::orderBy('nomAlumno', 'asc')->paginate(10)->get();
      $programming = programming::orderBy('hora')->paginate(5);
      return view('Programming.index', compact('texto','programming','studentList'));
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
      $newProgramming->categoriaDos = $request->categoriaDos;
      $newProgramming->eLocal = $request->eLocal;
      $newProgramming->eVisitante = $request->eVisitante;
      $newProgramming->hora = $request->hora;
      $newProgramming->fecha = $request->fecha;
      $newProgramming->jugadores_convocados = implode(',',$request->jugadores_convocados);

      try {
      $newProgramming->save();
      return redirect()->route('programming.index', $programming)->banner('Registro creado correctamente.');
      } catch (\Throwable $th) {
        return redirect()->route('programming.index', $programming)->dangerBanner('no se pudo crear nuevo registro => '.$th->getMessage());
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
        $programming->update($request->all());
      } catch (\Throwable $th) {
        return redirect()->route('programming.index', $programming)->dangerBanner('no se pudo actualizar registro por que => '.$th->getMessage());
      }
      return redirect()->route('programming.index', $programming)->banner('Registro actualizar correctamente.');
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
      } catch (\Throwable $th) {
        return redirect()->route('programming.index', $programming)->dangerBanner('no se pudo eliminar registro por que => '.$th->getMessage());
      }
      return redirect()->route('programming.index', $programming)->banner('Registro eliminado correctamente.');
    }
}
