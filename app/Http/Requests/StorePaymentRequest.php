<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Asumimos que los roles ya se manejan por middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                // Validación de unicidad compuesta: estudiante + mes + año
                Rule::unique('payments')->where(function ($query) {
                    return $query->where('student_id', $this->student_id)
                                 ->where('month', $this->month)
                                 ->where('year', $this->year);
                })
            ],
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'month.unique' => 'Este estudiante ya tiene un pago registrado para el mes y año seleccionados.',
        ];
    }
}
