<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'club_id' => 'nullable|exists:clubs,id',
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Categoria' => 'required|string',
            'fechaInscripcion' => 'required|date',
            'nomDeportista' => 'required|string|max:255',
            'numDocumento' => 'required|string|unique:students,numDocumento',
            'genero' => 'required|string',
            'PesoDeportista' => 'nullable|string',
            'EstaturaDeportista' => 'nullable|string',
            'RHDeportista' => 'nullable|string',
            'fechaNacimiento' => 'required|date',
            'Ciudad' => 'nullable|string',
            'Departamento' => 'nullable|string',
            'EPS' => 'nullable|string',
            'Colegio' => 'nullable|string',
            'Curso' => 'nullable|string',
            'numTelefonico' => 'required|string',
            'numTelefonicoUno' => 'nullable|string',
            'numTelefonicoDos' => 'nullable|string',
            'direccionDeportista' => 'nullable|string',
            'barrio' => 'nullable|string',
            'localidad' => 'nullable|string',
            'nombreMama' => 'nullable|string',
            'documentoMama' => 'nullable|string',
            'telefonoMama' => 'nullable|string',
            'direccionMama' => 'nullable|string',
            'correoMama' => 'nullable|email',
            'nombrePapa' => 'nullable|string',
            'documentoPapa' => 'nullable|string',
            'telefonoPapa' => 'nullable|string',
            'direccionPapa' => 'nullable|string',
            'correoPapa' => 'nullable|email',
            'enfermedades' => 'nullable|string',
            'medicamento' => 'nullable|string',
            'lesion' => 'nullable|string',
            'Cirugia' => 'nullable|string',
            'impedimento' => 'nullable|string',
            'lesionOM' => 'nullable|string',
        ];
    }
}
