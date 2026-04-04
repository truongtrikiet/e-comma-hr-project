<?php

use App\Enum\ActiveStatus;
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
        Schema::create('school_working_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->json('working_days');
            $table->integer('is_active')->default(ActiveStatus::ACTIVE->value);
            $table->time('working_hours_start')->nullable();
            $table->time('working_hours_end')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_working_calendars');
    }
};
