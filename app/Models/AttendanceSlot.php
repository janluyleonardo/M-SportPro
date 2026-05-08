<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'month',
        'year',
        'classes_used',
        'classes_allowed',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
