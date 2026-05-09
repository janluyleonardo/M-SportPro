<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programmings', function (Blueprint $table) {
            $table->decimal('costo_inscripcion', 10, 2)->default(0)->after('jugadores_convocados');
            $table->decimal('costo_arbitraje', 10, 2)->default(0)->after('costo_inscripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmings', function (Blueprint $table) {
            $table->dropColumn(['costo_inscripcion', 'costo_arbitraje']);
        });
    }
};
