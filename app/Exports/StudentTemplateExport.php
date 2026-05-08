<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            [
                'Juan Perez',           // nombre_deportista
                '1022348425',           // documento
                '2010',                 // categoria
                'Masculino',            // genero
                '2010-05-15',           // fecha_nacimiento
                '2024-01-01',           // fecha_inscripcion
                'O+',                   // rh
                '45',                   // peso
                '1.55',                 // estatura
                'Bogotá',               // ciudad
                'Cundinamarca',         // departamento
                'Sura',                 // eps
                'Colegio Ejemplo',      // colegio
                '501',                  // curso
                '3101234567',           // telefono
                'Maria Lopez',          // nombre_mama
                '52123456',             // documento_mama
                '3107654321',           // telefono_mama
                'Calle 123 #45-67',     // direccion_mama
                'mama@example.com',     // correo_mama
                'Pedro Perez',          // nombre_papa
                '79123456',             // documento_papa
                '3109876543',           // telefono_papa
                'Calle 123 #45-67',     // direccion_papa
                'papa@example.com',     // correo_papa
                'Calle 123 #45-67',     // direccion
                'Barrio Ejemplo',       // barrio
                'Kennedy',              // localidad
                'Ninguna',              // enfermedades
                'Ninguno',              // medicamento
                'Ninguna',              // lesion
                'Ninguna',              // cirugias
                'Ninguno',              // impedimento
                'Ninguna'               // lesion_om
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nombre_deportista',
            'documento',
            'categoria',
            'genero',
            'fecha_nacimiento',
            'fecha_inscripcion',
            'rh',
            'peso',
            'estatura',
            'ciudad',
            'departamento',
            'eps',
            'colegio',
            'curso',
            'telefono',
            'nombre_mama',
            'documento_mama',
            'telefono_mama',
            'direccion_mama',
            'correo_mama',
            'nombre_papa',
            'documento_papa',
            'telefono_papa',
            'direccion_papa',
            'correo_papa',
            'direccion',
            'barrio',
            'localidad',
            'enfermedades',
            'medicamento',
            'lesion',
            'cirugias',
            'impedimento',
            'lesion_om'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1    => ['font' => ['bold' => true]],
        ];
    }
}
