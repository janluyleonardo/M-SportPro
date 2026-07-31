<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class Payment extends Model
{
    use HasFactory, BelongsToClub;

    protected $fillable = [
        'club_id',
        'student_id',
        'month',
        'year',
        'amount',
        'status',
        'paid_at',
        'classes_available',
        'classes_used',
        'notes',
        'user_id',
        'voucher',
        'voucher_status',
        'rejection_reason',
        'waive_late_fee',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
