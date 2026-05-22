<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Club;
use App\Models\Module;
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

        // Sembrar los módulos de la plataforma
        $this->call(ModuleSeeder::class);

        // ── CREACIÓN DE CLUBES DE BASE ──────────────────────────────────────
        // Club 1: Jackeline FS
        $clubJackeline = Club::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Club deportivo jackeline fs',
                'is_active' => true,
                'logo' => null,
            ]
        );

        // Club 2: Rodesa
        $clubRodesa = Club::firstOrCreate(
            ['id' => 2],
            [
                'name' => 'club deportivo rodesa',
                'is_active' => true,
                'logo' => null,
            ]
        );

        // Asociar módulos a los clubes
        $moduleTorneos = Module::where('slug', 'tournaments')->first();
        $moduleFinancial = Module::where('slug', 'financial')->first();
        $moduleClasses = Module::where('slug', 'classes')->first();

        // Club Jackeline FS tiene Módulo Financiero y Módulo de Clases
        if ($clubJackeline && $moduleFinancial && $moduleClasses) {
            $clubJackeline->modules()->syncWithoutDetaching([
                $moduleFinancial->id,
                $moduleClasses->id,
            ]);
        }

        // Club Rodesa tiene Módulo de Torneos, Financiero y Clases
        if ($clubRodesa && $moduleTorneos && $moduleFinancial && $moduleClasses) {
            $clubRodesa->modules()->syncWithoutDetaching([
                $moduleTorneos->id,
                $moduleFinancial->id,
                $moduleClasses->id,
            ]);
        }

        // ── CREACIÓN DE USUARIOS DE PRUEBA ──────────────────────────────────
        $defaultPassword = bcrypt(env('DEFAULT_USER_PASSWORD', 'jackeline123'));

        // 1. Super Admin Global (Sin Club, is_super_admin = true)
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin General',
                'password' => $defaultPassword,
                'is_super_admin' => true,
                'club_id' => null,
            ]
        );
        $superAdmin->assignRole('Admin');

        // 2. Administrador del Club 1 (Jackeline FS)
        $adminJackeline = User::firstOrCreate(
            ['email' => 'admin@' . $domain],
            [
                'name' => 'Admin ' . $appName,
                'password' => $defaultPassword,
                'club_id' => $clubJackeline->id,
                'is_super_admin' => false,
            ]
        );
        $adminJackeline->assignRole('Admin');

        // 3. Profesor (Club 1)
        $profesor = User::firstOrCreate(
            ['email' => 'profesor@' . $domain],
            [
                'name' => 'Profesor de Prueba',
                'password' => $defaultPassword,
                'club_id' => $clubJackeline->id,
                'is_super_admin' => false,
            ]
        );
        $profesor->assignRole('Profesor');

        // 4. Padre de Familia (Club 1)
        $padre = User::firstOrCreate(
            ['email' => 'padre@' . $domain],
            [
                'name' => 'Padre de Familia',
                'password' => $defaultPassword,
                'club_id' => $clubJackeline->id,
                'documento_deportista' => '1001230001',
                'is_super_admin' => false,
            ]
        );
        $padre->assignRole('Padre');

        // 5. Deportista (Club 1)
        $deportista = User::firstOrCreate(
            ['email' => 'deportista@' . $domain],
            [
                'name' => 'Deportista de Prueba',
                'password' => $defaultPassword,
                'club_id' => $clubJackeline->id,
                'documento_deportista' => '1001230001',
                'is_super_admin' => false,
            ]
        );
        $deportista->assignRole('Deportista');

        // 6. Cliente (Club 1)
        $client = User::firstOrCreate(
            ['email' => 'cliente@' . $domain],
            [
                'name' => 'Cliente de Prueba',
                'password' => $defaultPassword,
                'club_id' => $clubJackeline->id,
                'is_super_admin' => false,
            ]
        );
        $client->assignRole('client');

        // 7. Administrador del Club 2 (Rodesa)
        $adminRodesa = User::firstOrCreate(
            ['email' => 'admin-rodesa@' . $domain],
            [
                'name' => 'admin rodesa',
                'password' => $defaultPassword,
                'club_id' => $clubRodesa->id,
                'is_super_admin' => false,
            ]
        );
        $adminRodesa->assignRole('Admin');

        // 8. Otro Administrador del Club 2 (Rodesa)
        $adminEdgar = User::firstOrCreate(
            ['email' => 'edgar-sabogal@rodesa.com'],
            [
                'name' => 'edgar sabogal',
                'password' => $defaultPassword,
                'club_id' => $clubRodesa->id,
                'is_super_admin' => false,
            ]
        );
        $adminEdgar->assignRole('Admin');

        // Crear estudiantes de prueba (Demo)
        $this->call(StudentSeeder::class);
    }
}
