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
            $table->decimal('deduct_from_carry', 5, 2)->default(0);
            $table->decimal('deduct_from_remaining', 5, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furloughs', function (Blueprint $table) {
            $table->dropColumn('deduct_from_carry');
            $table->dropColumn('deduct_from_remaining');
        });
    }
};
