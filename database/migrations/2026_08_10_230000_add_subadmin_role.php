<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Crear el rol SubAdmin si no existe
        Role::firstOrCreate(['name' => 'SubAdmin', 'guard_name' => 'web']);
    }

    public function down(): void
    {
        Role::where('name', 'SubAdmin')->delete();
    }
};
