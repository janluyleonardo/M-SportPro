<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('Photo');
            $table->string('Categoria');
            $table->date('fechaInscripcion');
            $table->string('nomDeportista');
            $table->bigInteger('numDocumento')->unique();
            $table->string('genero');
            $table->integer('PesoDeportista');
            $table->string('EstaturaDeportista');
            $table->string('RHDeportista');
            $table->date('fechaNacimiento');
            $table->string('Ciudad');
            $table->string('Departamento');
            $table->string('EPS');
            $table->string('Colegio');
            $table->string('Curso');
            $table->string('numTelefonico');
            $table->string('numTelefonicoUno')->nullable();
            $table->string('numTelefonicoDos')->nullable();
            $table->string('direccionDeportista');
            $table->string('barrio');
            $table->string('localidad');
            $table->string('nombreMama');
            $table->bigInteger('documentoMama');
            $table->string('telefonoMama');
            $table->string('direccionMama');
            $table->string('correoMama')->nullable();
            $table->string('nombrePapa');
            $table->bigInteger('documentoPapa');
            $table->string('telefonoPapa');
            $table->string('direccionPapa');
            $table->string('correoPapa')->nullable();
            $table->string('enfermedades');
            $table->string('medicamento');
            $table->string('lesion');
            $table->string('Cirugia');
            $table->string('impedimento')->nullable();
            $table->string('lesionOM')->nullable();
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
        Schema::dropIfExists('students');
    }
}
