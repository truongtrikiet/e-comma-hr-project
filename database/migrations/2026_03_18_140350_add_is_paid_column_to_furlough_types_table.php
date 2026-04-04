<?php

use App\Enum\IsPaid;
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
        Schema::table('furlough_types', function (Blueprint $table) {
            $table->integer('is_paid')->default(IsPaid::PAID->value)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furlough_types', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
};
