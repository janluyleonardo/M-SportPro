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

        // 1. Administrador
        $admin = User::factory()->create([
            'name' => 'Admin Jackeline',
            'email' => 'admin@jackeline.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');

        // 2. Profesor
        $profesor = User::factory()->create([
            'name' => 'Profesor de Prueba',
            'email' => 'profesor@jackeline.com',
            'password' => bcrypt('password'),
        ]);
        $profesor->assignRole('Profesor');

        // 3. Padre
        $padre = User::factory()->create([
            'name' => 'Padre de Familia',
            'email' => 'padre@jackeline.com',
            'password' => bcrypt('password'),
            'documento_deportista' => '1001230001',
        ]);
        $padre->assignRole('Padre');

        // 4. Deportista
        $deportista = User::factory()->create([
            'name' => 'Deportista de Prueba',
            'email' => 'deportista@jackeline.com',
            'password' => bcrypt('password'),
            'documento_deportista' => '1001230001',
        ]);
        $deportista->assignRole('Deportista');

        // 5. Client
        $client = User::factory()->create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@jackeline.com',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');

        // Crear estudiantes de prueba (Demo)
        $this->call(StudentSeeder::class);
    }
}
