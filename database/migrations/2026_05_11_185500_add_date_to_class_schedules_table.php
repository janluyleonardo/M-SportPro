<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ClassSchedule;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->date('date')->nullable()->after('day_of_week');
        });

        // Populate existing records with a date from the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $schedules = ClassSchedule::all();
        
        $dayMap = [
            'Lunes' => 0,
            'Martes' => 1,
            'Miércoles' => 2,
            'Jueves' => 3,
            'Viernes' => 4,
            'Sábado' => 5,
            'Domingo' => 6,
        ];

        foreach ($schedules as $schedule) {
            if (isset($dayMap[$schedule->day_of_week])) {
                $schedule->date = $startOfWeek->copy()->addDays($dayMap[$schedule->day_of_week])->toDateString();
                $schedule->save();
            }
        }
        
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
