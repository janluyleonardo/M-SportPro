<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class ClassSchedule extends Model
{
    use HasFactory, BelongsToClub;

    protected $fillable = [
        'club_id',
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'category',
        'user_id',
        'location',
        'observations',
        'active'
    ];

    protected $casts = [
        'date' => 'date',
        'active' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function categories()
    {
        return $this->hasMany(ClassScheduleCategory::class);
    }
}
