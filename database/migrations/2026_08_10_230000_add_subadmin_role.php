<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear el rol SubAdmin si no existe
        $roleSubAdmin = Role::firstOrCreate(['name' => 'SubAdmin', 'guard_name' => 'web']);

        // Asignar permisos a SubAdmin: TODOS EXCEPTO 'delete'
        // SubAdmin tiene los mismos permisos que Admin pero sin poder eliminar
        $subAdminPermissions = [
            'view dashboard',
            'view programming',
            'create programming',
            'edit programming',
            'view students',
            'create students',
            'edit students',
        ];

        // Asegurar que existan los permisos
        foreach ($subAdminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roleSubAdmin->syncPermissions($subAdminPermissions);
    }

    public function down(): void
    {
        Role::where('name', 'SubAdmin')->delete();
    }
};
