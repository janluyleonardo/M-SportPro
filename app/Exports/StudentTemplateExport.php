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
        // Retornar una colección vacía o con un ejemplo
        return collect([
            [
                'EJEMPLO: Juan Perez',
                '123456789',
                '2010',
                'Masculino',
                '2010-05-15',
                'O+',
                '45',
                '1.55',
                'Sura',
                'Colegio Ejemplo',
                '3101234567',
                'Maria Lopez',
                '3107654321',
                'Pedro Perez',
                '3109876543',
                'Calle 123 #45-67',
                'Barrio Ejemplo'
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
            'rh',
            'peso',
            'estatura',
            'eps',
            'colegio',
            'telefono',
            'nombre_mama',
            'telefono_mama',
            'nombre_papa',
            'telefono_papa',
            'direccion',
            'barrio'
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
