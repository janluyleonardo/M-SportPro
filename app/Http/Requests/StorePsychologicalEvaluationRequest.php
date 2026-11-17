<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePsychologicalEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && !$user->is_super_admin
            && $user->hasAnyRole(['Admin', 'Psicólogo']);
    }

    public function rules(): array
    {
        $criterionCodes = collect(config('psychological_followup.blocks'))
            ->flatMap(fn (array $block) => array_keys($block['criteria']))
            ->all();

        return [
            'student_id' => [
                'required',
                Rule::exists('students', 'id')
                    ->where('club_id', $this->user()->club_id)
                    ->where('activo', true),
            ],
            'evaluated_on' => ['required', 'date', 'before_or_equal:today'],
            'coach_name' => ['nullable', 'string', 'max:255'],
            'laterality' => ['nullable', Rule::in(['diestro', 'zurdo', 'ambidiestro'])],
            'somatotype' => ['nullable', Rule::in(['ectomorfo', 'mesomorfo', 'endomorfo'])],
            'height_cm' => ['nullable', 'numeric', 'gt:0', 'max:300'],
            'weight_kg' => ['nullable', 'numeric', 'gt:0', 'max:500'],
            'conclusions' => ['nullable', 'string', 'max:10000'],
            'proposal' => ['nullable', 'string', 'max:10000'],
            'criteria' => ['required', 'array', 'size:'.count($criterionCodes)],
            'criteria.*.rating' => ['required', 'integer', 'between:0,5'],
            'criteria.*.comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function after(): array
    {
        $criterionCodes = collect(config('psychological_followup.blocks'))
            ->flatMap(fn (array $block) => array_keys($block['criteria']))
            ->sort()
            ->values()
            ->all();

        return [
            function (Validator $validator) use ($criterionCodes) {
                $criteria = $this->input('criteria', []);

                if (!is_array($criteria)) {
                    return;
                }

                $submittedCodes = collect(array_keys($criteria))
                    ->sort()
                    ->values()
                    ->all();

                if ($submittedCodes !== $criterionCodes) {
                    $validator->errors()->add('criteria', 'La evaluación debe incluir exactamente todos los criterios configurados.');
                }
            },
        ];
    }
}
