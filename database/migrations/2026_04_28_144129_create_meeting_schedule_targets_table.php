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
        Schema::create('meeting_schedule_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_schedule_id')
                ->nullable()
                ->constrained('meeting_schedules')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('target_type');
            $table->unsignedBigInteger('target_id');
            $table->timestamps();

            $table->index(['meeting_schedule_id']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_schedule_targets');
    }
};
