<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class programming extends Model
{
    use HasFactory, SoftDeletes, BelongsToClub;

    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
    protected $fillable = [
      'club_id',
      'torneo',
      'cancha',
      'categoriaUno',
      'categoriaDos',
      'eLocal',
      'eVisitante',
      'hora',
      'fecha',
      'jugadores_convocados',
      'costo_inscripcion',
      'costo_arbitraje',
      'tournament_id',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function payments()
    {
        return $this->hasMany(ProgrammingPayment::class);
    }
}
