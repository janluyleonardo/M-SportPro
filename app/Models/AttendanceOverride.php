<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'authorized_by',
        'reason',
    ];

    /**
     * El estudiante al que se le concedió el override.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * La clase programada para la que aplica el override.
     */
    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    /**
     * El administrador que autorizó el override.
     */
    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }
}
