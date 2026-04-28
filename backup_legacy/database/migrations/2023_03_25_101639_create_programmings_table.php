<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programmings');
    }
}
