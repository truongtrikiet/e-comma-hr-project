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
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('code')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('school_id')
                ->after('user_id')
                ->nullable()
                ->constrained('schools')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
