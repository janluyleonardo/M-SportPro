<?php

namespace App\Http\Controllers;

use App\Models\Estudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $hoy = now()->format('Y-m-d');
        // return view('garden.incomes', compact('hoy'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        TODO://verificar esta funcionalidad en el controlador de estudiantes ya que toda la funcionalidad se migro alli
        $student = trim($request->get('texto'));
        $students = DB::table('students')
        // ->select('name','email','program','updated_at') //co esta linea se traen columnas especificas de la tabla
        ->where('nomAlumno','LIKE','%'.$student.'%')
        ->orWhere('numDocumento','LIKE','%'.$student.'%')
        ->orderByDesc('id')
        ->paginate(10);
        $mensaje ="";
        return view('students.index',compact('students','mensaje','student'));
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
        //
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
