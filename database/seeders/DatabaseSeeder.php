<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar el RoleSeeder primero
        $this->call(RoleSeeder::class);

        // Crear usuario Administrador
        $admin = User::factory()->create([
            'name' => 'Admin Jackeline',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Asignar rol de Admin
        $admin->assignRole('Admin');

        // Crear estudiantes de prueba (Demo)
        $this->call(StudentSeeder::class);

        // Opcional: Crear el usuario de prueba que mencionaste
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $testUser->assignRole('Admin'); // También le damos admin para la demo

        // Crear usuario Cliente (Demo restringida)
        $client = User::factory()->create([
            'name' => 'Padre de Familia',
            'email' => 'padre@example.com',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');
    }
}
