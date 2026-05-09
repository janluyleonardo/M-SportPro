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
        Schema::create('programming_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programming_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->boolean('pagado_inscripcion')->default(false);
            $table->boolean('pagado_arbitraje')->default(false);
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programming_payments');
    }
};
