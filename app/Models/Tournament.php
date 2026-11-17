<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class Tournament extends Model
{
    use HasFactory, SoftDeletes, BelongsToClub;

    protected $fillable = ['club_id', 'name', 'description', 'category', 'status', 'costo_total_inscripcion', 'costo_total_arbitraje', 'costo_arbitraje_partido'];

    public function programmings()
    {
        return $this->hasMany(programming::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_tournament');
    }
}
