<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected array $tables = [
        'students',
        'locations',
        'class_schedules',
        'tournaments',
        'products',
        'transactions',
        'payments',
        'programmings'
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'club_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs')->onDelete('cascade');
                });
                
                // Asignar los registros actuales al club base (ID 1) por defecto si existe el club y la tabla no está vacía
                if (DB::table('clubs')->where('id', 1)->exists() && DB::table($tableName)->exists()) {
                    DB::table($tableName)->update(['club_id' => 1]);
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'club_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['club_id']);
                    $table->dropColumn('club_id');
                });
            }
        }
    }
};
