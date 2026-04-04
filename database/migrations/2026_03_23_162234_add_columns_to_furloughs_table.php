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
        Schema::table('furloughs', function (Blueprint $table) {
            $table->decimal('number_of_days', 5, 2)->nullable()->after('end_time');
            $table->integer('use_balance')->nullable()->after('number_of_days');
            $table->foreignId('furlough_balance_id')
            ->nullable()->after('use_balance')
            ->constrained('furlough_balances')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furloughs', function (Blueprint $table) {
            $table->dropColumn('number_of_days');
            $table->dropColumn('use_balance');
            $table->dropForeign(['furlough_balance_id']);
            $table->dropColumn('furlough_balance_id');
        });
    }
};
