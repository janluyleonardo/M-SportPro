<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'description', 'active'];

    /**
     * Solo devuelve canchas activas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
