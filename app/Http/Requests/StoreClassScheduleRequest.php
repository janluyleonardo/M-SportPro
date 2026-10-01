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
            'club_id' => 'nullable|exists:clubs,id',
            'day_of_week' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'observations' => 'nullable|string|max:1000',
        ];
    }
}
