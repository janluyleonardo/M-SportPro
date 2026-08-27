<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleCategory extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'category',
    ];
}
