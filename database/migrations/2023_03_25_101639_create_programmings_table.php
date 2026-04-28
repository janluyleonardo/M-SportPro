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
        Schema::create('programmings', function (Blueprint $table) {
            $table->id();
            $table->string('torneo');
            $table->string('cancha');
            $table->string('categoriaUno');
            $table->string('categoriaDos');
            $table->string('eLocal');
            $table->string('eVisitante');
            $table->string('hora');
            $table->date('fecha');
            $table->longText('jugadores_convocados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmings');
    }
};
