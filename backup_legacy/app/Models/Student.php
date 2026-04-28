<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'Photo',
    'Categoria',
    'fechaInscripcion',
    'nomDeportista',
    'numDocumento',
    'genero',
    'PesoDeportista',
    'EstaturaDeportista',
    'RHDeportista',
    'fechaNacimiento',
    'Ciudad',
    'Departamento',
    'EPS',
    'Colegio',
    'Curso',
    'numTelefonico',
    'numTelefonicoUno',
    'numTelefonicoDos',
    'direccionDeportista',
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
    'enfermedades',
    'medicamento',
    'lesion',
    'Cirugia',
    'impedimento',
    'lesionOM',
  ];
}
