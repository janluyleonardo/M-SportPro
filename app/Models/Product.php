<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
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
