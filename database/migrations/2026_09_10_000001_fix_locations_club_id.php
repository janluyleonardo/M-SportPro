<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('locations')) {
            return;
        }

        $firstClubId = DB::table('clubs')->min('id');

        if ($firstClubId === null) {
            return;
        }

        DB::table('locations')
            ->whereNull('club_id')
            ->update(['club_id' => $firstClubId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op para conservar datos
    }
};
