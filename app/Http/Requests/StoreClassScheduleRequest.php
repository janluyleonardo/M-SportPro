<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'observations' => 'nullable|string',
        ];
    }
}
