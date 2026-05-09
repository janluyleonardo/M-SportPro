<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgrammingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'torneo' => 'required|string',
            'cancha' => 'required|string',
            'categoriaUno' => 'required|string',
            'categoriaDos' => 'nullable|string',
            'eLocal' => 'required|string',
            'eVisitante' => 'required|string',
            'hora' => 'required',
            'fecha' => 'required|date',
            'jugadores_convocados' => 'nullable|array',
            'costo_inscripcion' => 'nullable|numeric|min:0',
            'costo_arbitraje' => 'nullable|numeric|min:0',
            'tournament_id' => 'nullable|exists:tournaments,id',
        ];
    }
}
