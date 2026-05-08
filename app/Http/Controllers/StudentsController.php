<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $search = $request->input('search');
      
      $query = Student::orderBy('id', 'DESC');
      
      if ($search) {
          $query->where(function($q) use ($search) {
              $q->where('nomDeportista', 'LIKE', "%{$search}%")
                ->orWhere('Categoria', 'LIKE', "%{$search}%")
                ->orWhere('numDocumento', 'LIKE', "%{$search}%");
          });
      }
      
      $students = $query->paginate(5)->withQueryString();
      $studentsCount = Student::count(); 
      
      return view('students.index', compact('students', 'studentsCount', 'search'));
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
    public function store(StoreStudentRequest $request)
    {
      $validated = $request->validated();
      $newStudent = new Student($validated);

      if($request->hasfile('Photo')){
        $file = $request->file('Photo');
        $pathUrl = 'images/Photos/';
        $fileName = time()."-".$file->getClientOriginalName();
        $file->move(public_path($pathUrl), $fileName);
        $newStudent->Photo = $pathUrl . $fileName;
      }

      try {
        $newStudent->save();
        $newStudent->updateBalance(); // Inicializar saldo
      } catch (\Throwable $th) {
        return back()->withInput()->with('error', 'No se pudo agregar nuevo registro => '.$th->getMessage());
      }
      
      return redirect()->route('imprimir', $newStudent->id);
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

      // Optimización: Convertir imágenes a Base64 para acelerar el procesamiento de DomPDF
      $base64Logo = $this->imageToBase64(public_path('images/logo/LOGO.png'));
      $base64Photo = $student->Photo ? $this->imageToBase64(public_path($student->Photo)) : null;

      $pdf = Pdf::loadView('students.pdf', compact('student', 'base64Logo', 'base64Photo'));
      
      // Configuraciones adicionales para mejorar rendimiento
      $pdf->setPaper('letter', 'portrait');
      $pdf->setOptions([
          'isHtml5ParserEnabled' => true,
          'isRemoteEnabled' => true,
          'defaultFont' => 'Arial'
      ]);

      return $pdf->stream($student->nomDeportista.'.pdf');
    }

    /**
     * Auxiliar para convertir imágenes a Base64
     */
    private function imageToBase64($path)
    {
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return null;
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
    public function update(UpdateStudentRequest $request, Student $student)
    {
      $validated = $request->validated();
      $student->fill($validated);

      if($request->hasFile('Photo')){
        $file = $request->file('Photo');
        $pathUrl = 'images/Photos/';
        $fileName = time()."-".$file->getClientOriginalName();
        $file->move(public_path($pathUrl), $fileName);
        $student->Photo = $pathUrl . $fileName;
      }

      try {
        $student->save();
        $student->updateBalance();
        return redirect()->route('students.index')->with('success', 'Registro actualizado correctamente.');
      } catch (\Throwable $th) {
        return back()->withInput()->with('error', 'No pudimos actualizar el registro: '.$th->getMessage());
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
      $student->delete();
      return redirect()->route('students.index')->with('success', 'Registro eliminado correctamente.');
    }

    public function export()
    {
      try {
        return Excel::download(new StudentsExport, 'Registros.xlsx');
      } catch (\Throwable $th) {
        return redirect()->route('dashboard')->with('error', 'no se pudo generar registro excel => '.$th);
      }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            return back()->with('success', 'Deportistas importados correctamente.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error al importar deportistas: ' . $th->getMessage());
        }
    }
}
