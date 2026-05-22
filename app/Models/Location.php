<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class Location extends Model
{
    use BelongsToClub;

    protected $fillable = ['club_id', 'name', 'description', 'active'];

    /**
     * Solo devuelve canchas activas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
