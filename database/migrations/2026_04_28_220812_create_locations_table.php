<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Jackeline, La Isla, Berlín...
            $table->string('description')->nullable(); // Descripción opcional
            $table->boolean('active')->default(true);  // Desactivar sin eliminar
            $table->timestamps();
        });

        // Canchas iniciales del club
        DB::table('locations')->insert([
            ['name' => 'Jackeline', 'description' => 'Cancha principal del club', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Isla',   'description' => 'Cancha La Isla',            'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Berlín',    'description' => 'Cancha Berlín',             'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
