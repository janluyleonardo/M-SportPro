<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero cambiamos los tipos de columna a decimal
        Schema::table('programming_payments', function (Blueprint $table) {
            $table->decimal('pagado_inscripcion', 10, 2)->default(0)->change();
            $table->decimal('pagado_arbitraje', 10, 2)->default(0)->change();
        });

        // Actualizamos los registros existentes para que el '1' (true) se convierta en el valor real del costo
        $payments = DB::table('programming_payments')
            ->join('programmings', 'programming_payments.programming_id', '=', 'programmings.id')
            ->select(
                'programming_payments.id', 
                'programmings.costo_inscripcion', 
                'programmings.costo_arbitraje', 
                'programming_payments.pagado_inscripcion as old_ins', 
                'programming_payments.pagado_arbitraje as old_arb'
            )
            ->get();

        foreach ($payments as $p) {
            DB::table('programming_payments')->where('id', $p->id)->update([
                'pagado_inscripcion' => ($p->old_ins == 1) ? $p->costo_inscripcion : 0,
                'pagado_arbitraje' => ($p->old_arb == 1) ? $p->costo_arbitraje : 0,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programming_payments', function (Blueprint $table) {
            $table->boolean('pagado_inscripcion')->default(false)->change();
            $table->boolean('pagado_arbitraje')->default(false)->change();
        });
    }
};
