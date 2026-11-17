<?php

namespace App\Models;

use App\Traits\BelongsToClub;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PsychologicalEvaluation extends Model
{
    use BelongsToClub;

    protected $fillable = [
        'club_id',
        'student_id',
        'evaluator_id',
        'evaluator_name',
        'coach_name',
        'evaluated_on',
        'laterality',
        'somatotype',
        'height_cm',
        'weight_kg',
        'conclusions',
        'proposal',
    ];

    protected function casts(): array
    {
        return [
            'evaluated_on' => 'date',
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PsychologicalEvaluationItem::class, 'evaluation_id');
    }
}
