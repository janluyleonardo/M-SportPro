<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStdDeletedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_deleteds', function (Blueprint $table) {
            $table->id();
            $table->integer('idDeleted');
            $table->string('Photo');
            $table->string('Categoria');
            $table->date('fechaInscripcion');
            $table->string('nomDeportista');
            $table->bigInteger('numDocumento');
            $table->string('genero');
            $table->integer('PesoDeportista');
            $table->decimal('EstaturaDeportista');
            $table->string('RHDeportista');
            $table->date('fechaNacimiento');
            $table->string('Ciudad');
            $table->string('Departamento');
            $table->string('EPS');
            $table->string('Colegio');
            $table->string('Curso');
            $table->bigInteger('numTelefonico');
            $table->bigInteger('numTelefonicoUno')->nullable();
            $table->bigInteger('numTelefonicoDos')->nullable();
            $table->string('direccionDeportista');
            $table->string('barrio');
            $table->string('localidad');
            $table->string('nombreMama');
            $table->bigInteger('documentoMama');
            $table->bigInteger('telefonoMama');
            $table->string('direccionMama');
            $table->string('correoMama')->nullable();
            $table->string('nombrePapa');
            $table->bigInteger('documentoPapa');
            $table->bigInteger('telefonoPapa');
            $table->string('direccionPapa');
            $table->string('correoPapa')->nullable();
            $table->string('enfermedades');
            $table->string('medicamento');
            $table->string('lesion');
            $table->string('Cirugia');
            $table->string('impedimento');
            $table->string('lesionOM');
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
        Schema::dropIfExists('std_deleteds');
    }
}
