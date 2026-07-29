<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = $this->route('location') ? $this->route('location')->id : null;

        // Si es un toggle de estado, no se necesita validar name ni description
        if ($this->has('toggle_active')) {
            return [
                'toggle_active' => 'required',
            ];
        }

        return [
            'club_id' => 'nullable|exists:clubs,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('locations', 'name')->ignore($locationId),
            ],
            'description' => 'nullable|string|max:255',
        ];
    }
}
