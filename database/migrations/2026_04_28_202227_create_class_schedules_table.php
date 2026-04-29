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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week'); // Lunes, Martes...
            $table->time('start_time');
            $table->time('end_time');
            $table->string('category'); // Ejemplo: 2010, 2011
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Profesor (User)
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
