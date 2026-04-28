<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

use PDF;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $mensaje = "";
      $students = Student::orderBy('id', 'DESC')->paginate(15);
      $studentsCount = Student::all();
      return view('students.index', compact('students','mensaje','studentsCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
      $newStudent = new Student();

      if($request->hasfile('Photo')){
        $file = $request->file('Photo');
        $pathUrl = 'images/Photos/';
        $fileName = time()."-".$file->getClientOriginalName();
        $uploadSuccess = $request->file('Photo')->move($pathUrl, $fileName);
        $newStudent->Photo = $pathUrl . $fileName;
      }
      $newStudent->Categoria = $request->Categoria;
      $newStudent->fechaInscripcion = $request->fechaInscripcion;
      $newStudent->nomDeportista = $request->nomDeportista;
      $newStudent->numDocumento = $request->numDocumento;
      $newStudent->genero = $request->genero;
      $newStudent->PesoDeportista = $request->PesoDeportista;
      $newStudent->EstaturaDeportista = $request->EstaturaDeportista;
      $newStudent->RHDeportista = $request->RHDeportista;
      $newStudent->fechaNacimiento = $request->fechaNacimiento;
      $newStudent->Ciudad = $request->Ciudad;
      $newStudent->Departamento = $request->Departamento;
      $newStudent->EPS = $request->EPS;
      $newStudent->Colegio = $request->Colegio;
      $newStudent->Curso = $request->Curso;
      $newStudent->numTelefonico = $request->numTelefonico;
      $newStudent->numTelefonicoUno = $request->numTelefonicoUno;
      $newStudent->numTelefonicoDos = $request->numTelefonicoDos;
      $newStudent->direccionDeportista = $request->direccionDeportista;
      $newStudent->barrio = $request->barrio;
      $newStudent->localidad = $request->localidad;
      $newStudent->nombreMama = $request->nombreMama;
      $newStudent->documentoMama = $request->documentoMama;
      $newStudent->telefonoMama = $request->telefonoMama;
      $newStudent->direccionMama = $request->direccionMama;
      $newStudent->correoMama = $request->correoMama;
      $newStudent->nombrePapa = $request->nombrePapa;
      $newStudent->documentoPapa = $request->documentoPapa;
      $newStudent->telefonoPapa = $request->telefonoPapa;
      $newStudent->direccionPapa = $request->direccionPapa;
      $newStudent->correoPapa = $request->correoPapa;
      $newStudent->enfermedades = $request->enfermedades;
      $newStudent->medicamento = $request->medicamento;
      $newStudent->lesion = $request->lesion;
      $newStudent->Cirugia = $request->Cirugia;
      $newStudent->impedimento = $request->impedimento;
      $newStudent->lesionOM = $request->lesionOM;

      try {
        //code...
        $newStudent->save();
      } catch (\Throwable $th) {
        // throw $th;
        return redirect()->route('students.show', $newStudent)->bannerdanger('no se pudo agregar nuevo registro => '.$th);
      }
      // $student = Student::create($request->all());
      return redirect()->route('students.show', $newStudent)->banner('Registro creado correctamente.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {
      $student = Student::findOrFail($id);
      $pdf = PDF::loadView('students.pdf', compact('student'));
      return $pdf->stream($student->nomDeportista.'.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Student $student)
    {
      $mensaje ="Registro actualizado correctamente";
      return view('students.show', compact('student','mensaje'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
      $hoy = now()->format('Y-m-d');
      $id = $student->id;
      return view('students.edit', compact('student','hoy'));
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
      $student = Student::findOrFail($id);

      if($request->hasfile('Photo')){
        $file = $request->file('Photo');
        $pathUrl = 'images/Photos/';
        $fileName = time()."-".$file->getClientOriginalName();
        $uploadSuccess = $request->file('Photo')->move($pathUrl, $fileName);
        $student->Photo = $pathUrl . $fileName;
      }
      $student->Categoria = $request->Categoria;
      $student->fechaInscripcion = $request->fechaInscripcion;
      $student->nomDeportista = $request->nomDeportista;
      $student->numDocumento = $request->numDocumento;
      $student->genero = $request->genero;
      $student->PesoDeportista = $request->PesoDeportista;
      $student->EstaturaDeportista = $request->EstaturaDeportista;
      $student->RHDeportista = $request->RHDeportista;
      $student->fechaNacimiento = $request->fechaNacimiento;
      $student->Ciudad = $request->Ciudad;
      $student->Departamento = $request->Departamento;
      $student->EPS = $request->EPS;
      $student->Colegio = $request->Colegio;
      $student->Curso = $request->Curso;
      $student->numTelefonico = $request->numTelefonico;
      $student->numTelefonicoUno = $request->numTelefonicoUno;
      $student->numTelefonicoDos = $request->numTelefonicoDos;
      $student->direccionDeportista = $request->direccionDeportista;
      $student->barrio = $request->barrio;
      $student->localidad = $request->localidad;
      $student->nombreMama = $request->nombreMama;
      $student->documentoMama = $request->documentoMama;
      $student->telefonoMama = $request->telefonoMama;
      $student->direccionMama = $request->direccionMama;
      $student->correoMama = $request->correoMama;
      $student->nombrePapa = $request->nombrePapa;
      $student->documentoPapa = $request->documentoPapa;
      $student->telefonoPapa = $request->telefonoPapa;
      $student->direccionPapa = $request->direccionPapa;
      $student->correoPapa = $request->correoPapa;
      $student->enfermedades = $request->enfermedades;
      $student->medicamento = $request->medicamento;
      $student->lesion = $request->lesion;
      $student->Cirugia = $request->Cirugia;
      $student->impedimento = $request->impedimento;
      $student->lesionOM = $request->lesionOM;

      try {
        $student->update();
        return redirect()->route('students.show', compact('student'))->banner('Registro actualizado correctamente.');
      } catch (\Throwable $th) {
        return redirect()->route('students.show')->dangerBanner('No pudimos actualizar el registro por favor valide los datos ingresados '.$th);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
      return $student;
      // return redirect()->route('students.index', $student);
      return redirect()->route('students.index', compact('student'))->banner('Registro eliminado correctamente.');
    }

    public function export()
    {
      try {
        return Excel::download(new StudentsExport, 'Registros.xlsx');
      } catch (\Throwable $th) {
        return redirect()->route('students.show')->bannerdanger('no se pudo generar registro excel => '.$th);
      }
    }
}
