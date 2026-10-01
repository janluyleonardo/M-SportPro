<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Módulo de Torneos',
                'slug' => 'tournaments',
                'description' => 'Gestión de torneos y programaciones',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Mensualidades',
                'slug' => 'financial',
                'description' => 'Gestión de pagos, cuotas y facturación mensual',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Clases',
                'slug' => 'classes',
                'description' => 'Programación de clases y control de asistencia',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Inventario',
                'slug' => 'inventory',
                'description' => 'Gestión de productos, stock e inventario',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Tesorería',
                'slug' => 'treasury',
                'description' => 'Control de caja, movimientos y tesorería',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Nómina',
                'slug' => 'payroll',
                'description' => 'Gestión de pagos a profesores y historial de nómina',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Canchas',
                'slug' => 'locations',
                'description' => 'Gestión de canchas y disponibilidad para clases y partidos',
                'is_active' => true,
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
