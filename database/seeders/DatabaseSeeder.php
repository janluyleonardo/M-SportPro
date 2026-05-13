<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
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
        // Obtener el dominio basado en el nombre de la app
        $appName = config('app.name', 'Jackeline');
        $domain = Str::slug($appName) . '.com';

        // Ejecutar el RoleSeeder primero
        $this->call(RoleSeeder::class);

        // 1. Administrador
        $admin = User::factory()->create([
            'name' => 'Admin ' . $appName,
            'email' => 'admin@' . $domain,
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');

        // 2. Profesor
        $profesor = User::factory()->create([
            'name' => 'Profesor de Prueba',
            'email' => 'profesor@' . $domain,
            'password' => bcrypt('password'),
        ]);
        $profesor->assignRole('Profesor');

        // 3. Padre
        $padre = User::factory()->create([
            'name' => 'Padre de Familia',
            'email' => 'padre@' . $domain,
            'password' => bcrypt('password'),
            'documento_deportista' => '1001230001',
        ]);
        $padre->assignRole('Padre');

        // 4. Deportista
        $deportista = User::factory()->create([
            'name' => 'Deportista de Prueba',
            'email' => 'deportista@' . $domain,
            'password' => bcrypt('password'),
            'documento_deportista' => '1001230001',
        ]);
        $deportista->assignRole('Deportista');

        // 5. Client
        $client = User::factory()->create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@' . $domain,
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');

        // Crear estudiantes de prueba (Demo)
        $this->call(StudentSeeder::class);
    }
}
