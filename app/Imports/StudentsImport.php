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

        $numDocumento = $row['documento'] ?? $row['numero_documento'] ?? null;

        // Si no hay documento, generamos uno temporal para evitar errores, 
        // aunque lo ideal es que siempre venga en la plantilla.
        $searchKey = ['numDocumento' => $numDocumento ?? rand(10000000, 99999999)];

        return Student::updateOrCreate($searchKey, [
            'nomDeportista'       => $row['nombre_deportista'] ?? $row['nombre'] ?? 'Sin nombre',
            'Categoria'           => $row['categoria'] ?? 'Sin categoría',
            'genero'              => $row['genero'] ?? 'No especificado',
            'fechaNacimiento'     => $this->transformDate($row['fecha_nacimiento'] ?? null),
            'fechaInscripcion'    => $this->transformDate($row['fecha_inscripcion'] ?? null) ?? now()->format('Y-m-d'),
            'RHDeportista'        => $row['rh'] ?? $row['tipo_sangre'] ?? 'O+',
            'PesoDeportista'      => $row['peso'] ?? 0,
            'EstaturaDeportista'  => $row['estatura'] ?? '0',
            'Ciudad'              => $row['ciudad'] ?? 'Bogotá',
            'Departamento'        => $row['departamento'] ?? 'Cundinamarca',
            'EPS'                 => $row['eps'] ?? 'No especificada',
            'Colegio'             => $row['colegio'] ?? 'No especificado',
            'Curso'               => $row['curso'] ?? 'No especificado',
            'numTelefonico'       => $row['telefono'] ?? $row['celular'] ?? '0000000000',
            'nombreMama'          => $row['nombre_mama'] ?? 'No especificado',
            'documentoMama'       => $row['documento_mama'] ?? 0,
            'telefonoMama'        => $row['telefono_mama'] ?? '0000000000',
            'direccionMama'       => $row['direccion_mama'] ?? 'No especificada',
            'correoMama'          => $row['correo_mama'] ?? null,
            'nombrePapa'          => $row['nombre_papa'] ?? 'No especificado',
            'documentoPapa'       => $row['documento_papa'] ?? 0,
            'telefonoPapa'        => $row['telefono_papa'] ?? '0000000000',
            'direccionPapa'       => $row['direccion_papa'] ?? 'No especificada',
            'correoPapa'          => $row['correo_papa'] ?? null,
            'direccionDeportista' => $row['direccion'] ?? 'No especificada',
            'barrio'              => $row['barrio'] ?? 'No especificado',
            'localidad'           => $row['localidad'] ?? 'No especificada',
            'Photo'               => '',
            'enfermedades'        => $row['enfermedades'] ?? 'Ninguna',
            'medicamento'         => $row['medicamento'] ?? 'Ninguno',
            'lesion'              => $row['lesion'] ?? 'Ninguna',
            'Cirugia'             => $row['cirugias'] ?? $row['cirugia'] ?? 'Ninguna',
            'impedimento'         => $row['impedimento'] ?? 'Ninguno',
            'lesionOM'            => $row['lesion_om'] ?? 'Ninguna',
        ]);
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
