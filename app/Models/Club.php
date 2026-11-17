<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function hasModule($slug)
    {
        return $this->modules()->where('slug', $slug)->exists();
    }
}
