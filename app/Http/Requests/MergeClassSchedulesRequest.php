<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeClassSchedulesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_ids' => 'required|array|min:2',
            'schedule_ids.*' => 'required|integer|distinct|exists:class_schedules,id',
            'user_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'observations' => 'required|string|min:5|max:1000',
        ];
    }
}
