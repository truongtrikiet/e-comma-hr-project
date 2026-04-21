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
        Schema::table('furlough_balances', function (Blueprint $table) {
            $table->integer('carry_remaining_days')->after('remaining_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furlough_balances', function (Blueprint $table) {
            $table->dropColumn('carry_remaining_days');
        });
    }
};
