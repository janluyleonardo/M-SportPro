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
        Schema::create('attendance_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->integer('classes_used')->default(0);
            $table->integer('classes_allowed')->default(8);
            $table->timestamps();

            // Evitar duplicados para un mismo mes/año por estudiante
            $table->unique(['student_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_slots');
    }
};
