<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'programming_id',
        'student_id',
        'pagado_inscripcion',
        'pagado_arbitraje',
        'monto_total',
        'fecha_pago',
    ];

    public function programming()
    {
        return $this->belongsTo(programming::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
