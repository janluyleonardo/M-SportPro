<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programming extends Model
{
    use HasFactory;

    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
    protected $fillable = [
      'torneo',
      'cancha',
      'categoriaUno',
      'categoriaDos',
      'eLocal',
      'eVisitante',
      'hora',
      'fecha',
      'jugadores_convocados',
    ];
}
