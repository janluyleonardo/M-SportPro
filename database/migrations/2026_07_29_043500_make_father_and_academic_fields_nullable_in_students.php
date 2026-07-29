<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hacer nullable los campos del padre (acudiente 2) y los campos
     * académicos que fueron removidos del formulario de registro.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Campos del padre (acudiente 2) - ahora opcionales
            $table->string('nombrePapa')->nullable()->change();
            $table->bigInteger('documentoPapa')->nullable()->change();
            $table->string('telefonoPapa')->nullable()->change();
            $table->string('direccionPapa')->nullable()->change();

            // Campos académicos removidos del formulario
            $table->string('Departamento')->nullable()->change();
            $table->string('EPS')->nullable()->change();
            $table->string('Colegio')->nullable()->change();
            $table->string('Curso')->nullable()->change();
        });
    }

    /**
     * Revertir los campos a NOT NULL.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nombrePapa')->nullable(false)->change();
            $table->bigInteger('documentoPapa')->nullable(false)->change();
            $table->string('telefonoPapa')->nullable(false)->change();
            $table->string('direccionPapa')->nullable(false)->change();

            $table->string('Departamento')->nullable(false)->change();
            $table->string('EPS')->nullable(false)->change();
            $table->string('Colegio')->nullable(false)->change();
            $table->string('Curso')->nullable(false)->change();
        });
    }
};
