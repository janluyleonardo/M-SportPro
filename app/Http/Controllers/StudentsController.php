<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Exports\StudentTemplateExport;
use App\Imports\StudentsImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\CustomLogger;
use Illuminate\Support\Str;

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
      $query->where(function ($q) use ($search) {
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
    $clubs = auth()->user()->is_super_admin ? \App\Models\Club::all() : collect();
    return view('students.create', compact('clubs'));
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
    if (!auth()->user()->hasRole('Admin')) {
      unset($validated['becado']);
    } else {
      $validated['becado'] = $request->boolean('becado');
    }
    $newStudent = new Student($validated);

    if ($request->hasfile('Photo')) {
      $file = $request->file('Photo');
      $pathUrl = 'images/Photos/';
      $slug = Str::slug($validated['nomDeportista']);
      $extension = $file->getClientOriginalExtension();
      $fileName = $slug . '-' . time() . '.' . $extension;
      $file->move(public_path($pathUrl), $fileName);
      $newStudent->Photo = $pathUrl . $fileName;
    }

    try {
      $newStudent->save();
      $newStudent->updateBalance(); // Inicializar saldo
      return redirect()->route("students.index")->with("success", "Registro creado exitosamente");
      // return redirect()->route('imprimir', $newStudent->id);
    } catch (\Throwable $th) {
      CustomLogger::logException($th);
      return back()->withInput()->with('error', 'No se pudo agregar nuevo registro => ' . $th->getMessage());
    }
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

    // Dynamically load the club logo (fallback to default)
    $logoPath = 'images/logo/LOGO.png';
    if ($student->club && $student->club->logo && file_exists(public_path($student->club->logo))) {
      $logoPath = $student->club->logo;
    }

    // Optimización: Convertir imágenes a Base64 para acelerar el procesamiento de DomPDF
    $base64Logo = $this->imageToBase64(public_path($logoPath));
    $base64Photo = $student->Photo ? $this->imageToBase64(public_path($student->Photo)) : null;

    $pdf = Pdf::loadView('students.pdf', compact('student', 'base64Logo', 'base64Photo'));

    // Configuraciones adicionales para mejorar rendimiento
    $pdf->setPaper('letter', 'portrait');
    $pdf->setOptions([
      'isHtml5ParserEnabled' => true,
      'isRemoteEnabled' => true,
      'defaultFont' => 'Arial'
    ]);

    return $pdf->stream($student->nomDeportista . '.pdf');
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
    $mensaje = "Registro actualizado correctamente";
    return view('students.show', compact('student', 'mensaje'));
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
    $clubs = auth()->user()->is_super_admin ? \App\Models\Club::all() : collect();
    return view('students.edit', compact('student', 'hoy', 'clubs'));
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
    if (!auth()->user()->hasRole('Admin')) {
      unset($validated['becado']);
    } else {
      $validated['becado'] = $request->boolean('becado');
    }
    $student->fill($validated);

    if ($request->hasFile('Photo')) {
      $file = $request->file('Photo');
      $pathUrl = 'images/Photos/';
      $fileName = time() . "-" . $file->getClientOriginalName();
      $file->move(public_path($pathUrl), $fileName);
      $student->Photo = $pathUrl . $fileName;
    }

    try {
      $student->save();
      $student->updateBalance();
      return redirect()->route('students.index')->with('success', 'Registro actualizado correctamente.');
    } catch (\Throwable $th) {
      CustomLogger::logException($th);
      return back()->withInput()->with('error', 'No pudimos actualizar el registro: ' . $th->getMessage());
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
      CustomLogger::logException($th);
      return redirect()->route('dashboard')->with('error', 'no se pudo generar registro excel => ' . $th->getMessage());
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
      return $this->handleImportError($th);
    }
  }

  /**
   * Procesa y limpia los errores de importación para mostrarlos de forma amigable.
   */
  private function handleImportError(\Throwable $th)
  {
    CustomLogger::logException($th);
    $message = 'Ocurrió un error inesperado al importar.';

    if ($th instanceof \Illuminate\Database\QueryException) {
      $errorCode = $th->errorInfo[1] ?? null;
      $errorMsg = $th->errorInfo[2] ?? '';

      // Mapeo de columnas de base de datos a nombres legibles
      $columnMap = [
        'nomDeportista' => 'Nombre del Deportista',
        'numDocumento' => 'Número de Documento',
        'Categoria' => 'Categoría',
        'genero' => 'Género',
        'fechaNacimiento' => 'Fecha de Nacimiento',
        'fechaInscripcion' => 'Fecha de Inscripción',
        'RHDeportista' => 'RH',
        'PesoDeportista' => 'Peso',
        'EstaturaDeportista' => 'Estatura',
        'Ciudad' => 'Ciudad',
        'Departamento' => 'Departamento',
        'EPS' => 'EPS',
        'Colegio' => 'Colegio',
        'Curso' => 'Curso',
        'numTelefonico' => 'Teléfono',
        'nombreMama' => 'Nombre de la Madre',
        'documentoMama' => 'Documento de la Madre',
        'telefonoMama' => 'Teléfono de la Madre',
        'direccionMama' => 'Dirección de la Madre',
        'correoMama' => 'Correo de la Madre',
        'nombrePapa' => 'Nombre del Padre',
        'documentoPapa' => 'Documento del Padre',
        'telefonoPapa' => 'Teléfono del Padre',
        'direccionPapa' => 'Dirección del Padre',
        'correoPapa' => 'Correo del Padre',
        'direccionDeportista' => 'Dirección del Deportista',
        'barrio' => 'Barrio',
        'localidad' => 'Localidad',
      ];

      if ($errorCode === 1048) {
        if (preg_match("/Column '([^']+)' cannot be null/i", $errorMsg, $matches)) {
          $column = $matches[1];
          $friendlyName = $columnMap[$column] ?? $column;
          $message = "El campo '{$friendlyName}' no puede estar vacío en el archivo Excel.";
        } else {
          $message = "Hay campos obligatorios vacíos en el archivo Excel.";
        }
      } elseif ($errorCode === 1364) {
        if (preg_match("/Field '([^']+)' doesn't have a default value/i", $errorMsg, $matches)) {
          $column = $matches[1];
          $friendlyName = $columnMap[$column] ?? $column;
          $message = "El campo '{$friendlyName}' es obligatorio y no fue suministrado.";
        } else {
          $message = "Faltan campos obligatorios en el archivo Excel.";
        }
      } elseif ($errorCode === 1062) {
        if (preg_match("/Duplicate entry '([^']+)' for key/i", $errorMsg, $matches)) {
          $value = $matches[1];
          $message = "Ya existe un registro con el valor '{$value}' (ej. documento duplicado).";
        } else {
          $message = "Hay datos duplicados en el archivo Excel.";
        }
      } else {
        $message = "Error en base de datos: " . $errorMsg;
      }
    } else {
      $message = $th->getMessage();
    }

    return back()->with('error', 'Error al importar deportistas: ' . $message);
  }

  public function exportTemplate()
  {
    try {
      return Excel::download(new StudentTemplateExport, 'Plantilla_Deportistas.xlsx');
    } catch (\Throwable $th) {
      CustomLogger::logException($th);
      return redirect()->route('dashboard')->with('error', 'no se pudo generar la plantilla excel => ' . $th->getMessage());
    }
  }
}
