<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class Product extends Model
{
    use HasFactory, BelongsToClub;

    protected $fillable = [
        'club_id',
        'name',
        'description',
        'price',
        'stock',
        'image'
    ];

    /**
     * Scope para productos con stock disponible
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
