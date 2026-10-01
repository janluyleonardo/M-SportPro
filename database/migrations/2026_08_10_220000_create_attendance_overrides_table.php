<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('authorized_by')->constrained('users')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->timestamps();

            // Un solo override por alumno por clase
            $table->unique(['student_id', 'class_schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_overrides');
    }
};
