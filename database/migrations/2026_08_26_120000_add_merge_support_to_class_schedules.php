<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('observations');
        });

        Schema::create('class_schedule_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->timestamps();
            $table->unique(['class_schedule_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedule_categories');

        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
