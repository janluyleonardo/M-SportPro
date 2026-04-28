<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stdDeleted extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

  protected $table = 'std_deleteds';

  protected $fillable = [
    'nivel',
    'fechaMatricula',
    'nomAlumno',
    'fechaNacimiento',
    'genero',
    'EdadAlumno',
    'documentType',
    'numDocumento',
    'Esalud',
    'EPS',
    'numTelefonico',
    'numTelefonicoUno',
    'numTelefonicoDos',
    'direccionAlumno',
    'barrio',
    'localidad',
    'nombreMama',
    'documentoMama',
    'telefonoMama',
    'direccionMama',
    'correoMama',
    'nombrePapa',
    'documentoPapa',
    'telefonoPapa',
    'direccionPapa',
    'correoPapa',
    'nomPriRes',
    'docPriRes',
    'dirPriRes',
    'telPriRes',
    'nomSegRes',
    'docSegRes',
    'dirSegRes',
    'telSegRes',
    'nomTerRes',
    'docTerRes',
    'dirTerRes',
    'telTerRes',
  ];
}
