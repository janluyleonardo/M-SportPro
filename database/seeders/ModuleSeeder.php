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
                'name' => 'Módulo Financiero',
                'slug' => 'financial',
                'description' => 'Gestión de pagos, transacciones y facturación',
                'is_active' => true,
            ],
            [
                'name' => 'Módulo de Clases',
                'slug' => 'classes',
                'description' => 'Programación de clases y control de asistencia',
                'is_active' => true,
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
