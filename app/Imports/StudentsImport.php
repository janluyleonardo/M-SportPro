<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Limpiar espacios en blanco de las llaves (cabeceras) si existen
        $row = array_combine(
            array_map(fn($k) => strtolower(str_replace(' ', '_', trim($k))), array_keys($row)),
            array_values($row)
        );

        return new Student([
            'nomDeportista'       => $row['nombre_deportista'] ?? $row['nombre'] ?? null,
            'numDocumento'        => $row['documento'] ?? $row['numero_documento'] ?? null,
            'Categoria'           => $row['categoria'] ?? null,
            'genero'              => $row['genero'] ?? null,
            'fechaNacimiento'     => $row['fecha_nacimiento'] ?? null,
            'fechaInscripcion'    => $row['fecha_inscripcion'] ?? now()->format('Y-m-d'),
            'RHDeportista'        => $row['rh'] ?? $row['tipo_sangre'] ?? null,
            'PesoDeportista'      => $row['peso'] ?? null,
            'EstaturaDeportista'  => $row['estatura'] ?? null,
            'EPS'                 => $row['eps'] ?? null,
            'Colegio'             => $row['colegio'] ?? null,
            'numTelefonico'       => $row['telefono'] ?? $row['celular'] ?? null,
            'nombreMama'          => $row['nombre_mama'] ?? null,
            'telefonoMama'        => $row['telefono_mama'] ?? null,
            'nombrePapa'          => $row['nombre_papa'] ?? null,
            'telefonoPapa'        => $row['telefono_papa'] ?? null,
            'direccionDeportista' => $row['direccion'] ?? null,
            'barrio'              => $row['barrio'] ?? null,
        ]);
    }
}
