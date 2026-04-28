<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudent;
use Illuminate\Http\Request;
use App\Models\controlHealth;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $texto = trim($request->get('texto'));
      // $registros = controlHealth::all();
      // $cantMedico = controlHealth::where('examenmedico','>=','2023-01-01')->count();
      // $cantVisual = controlHealth::where('examenvisual','>=','2023-01-01')->count();
      // $cantAuditivo = controlHealth::where('examenauditivo','>=','2023-01-01')->count();
      // $cantOdontologico = controlHealth::where('examenodontologico','>=','2023-01-01')->count();
      // $cantCreDesarrollo = controlHealth::where('cdActual','>=','2023-01-01')->count();
      // $healths = controlHealth::where('nomAlumno','LIKE','%'.$texto.'%')
      //             ->orWhere('numDocumento','LIKE','%'.$texto.'%')
      //             ->orderBy('id')
      //             ->paginate(5);
      // $healths = controlHealth::orderBy('id', 'desc')->paginate(5);
      // // $healths = controlHealth::where('nomAlumno','LIKE','%'.$texto.'%')
      // //             ->orWhere('numDocumento','LIKE','%'.$texto.'%')
      // //             ->orderBy('id')
      // //             ->paginate();
      return view('health.index',compact('registros','healths','texto','cantMedico','cantVisual','cantAuditivo','cantOdontologico','cantCreDesarrollo'));
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
    public function store(StoreStudent $request)
    {
      //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
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
      $date = Carbon::parse($request->cdActual);
      $final = $date->addMonths(6)->format('Y-m-d');
      $request->merge([
        'nomAlumno' =>($request->nomAlumno),
      ]);
      // $health = controlHealth::findOrFail($id);
      // $health->nomAlumno = $request->nomAlumno;
      // $health->numDocumento = $request->numDocumento;
      // $health->edadAlumno = $request->edadAlumno;
      // $health->examenMedico = $request->examenMedico;
      // $health->examenVisual = $request->examenVisual;
      // $health->examenAuditivo = $request->examenAuditivo;
      // $health->examenOdontologico = $request->examenOdontologico;
      // $health->cdActual = $request->cdActual;
      // $health->cdProximo = $final;
      // $health->cdEntregado = $request->cdEntregado;
      // try {
      // $health->update();
      // $texto = $health->numDocumento;
      // return redirect()->route('health.index', compact('texto','health'))->banner('Registro actualizado correctamente.');
      // } catch (\Throwable $th) {
      //   return redirect()->route('health.index')->dangerBanner('Registro duplicado por favor validar el documento del alumno al cual le esta ingresando los datos');
      // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
