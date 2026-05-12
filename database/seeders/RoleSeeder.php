<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'view dashboard',
            'view programming',
            'create programming',
            'edit programming',
            'delete programming',
            'view students',
            'create students',
            'edit students',
            'delete students',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear Roles y asignar permisos
        
        // ADMIN: CRUD completo
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->syncPermissions(Permission::all());

        // PROFESOR: Dashboard + Programación/Estudiantes (Crear/Editar pero NO eliminar)
        $roleProfesor = Role::firstOrCreate(['name' => 'Profesor']);
        $roleProfesor->syncPermissions([
            'view dashboard',
            'view programming',
            'create programming',
            'edit programming',
            'view students',
            'create students',
            'edit students',
        ]);

        // PADRE: Dashboard + Programación (Solo lectura)
        $rolePadre = Role::firstOrCreate(['name' => 'Padre']);
        $rolePadre->syncPermissions([
            'view dashboard',
            'view programming',
        ]);

        // CLIENT: Acceso limitado (Solo lectura)
        $roleClient = Role::firstOrCreate(['name' => 'client']);
        $roleClient->syncPermissions([
            'view dashboard',
            'view programming',
            'view students',
        ]);

        // DEPORTISTA: Solo lectura de programación y dashboard
        $roleDeportista = Role::firstOrCreate(['name' => 'Deportista']);
        $roleDeportista->syncPermissions([
            'view dashboard',
            'view programming',
        ]);
    }
}
