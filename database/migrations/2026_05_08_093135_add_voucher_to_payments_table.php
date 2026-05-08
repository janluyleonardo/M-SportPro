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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('voucher')->nullable()->after('status');
            $table->enum('voucher_status', ['pending', 'approved', 'rejected'])->nullable()->after('voucher');
            $table->text('rejection_reason')->nullable()->after('voucher_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['voucher', 'voucher_status', 'rejection_reason']);
        });
    }
};
