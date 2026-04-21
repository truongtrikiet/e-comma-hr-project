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
        Schema::create('candidate_screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->foreignId('ai_profile_id')
                ->constrained('ai_profiles')
                ->cascadeOnDelete();
            $table->string('position_type')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('candidate_email')->nullable();
            $table->string('candidate_phone_number')->nullable();
            $table->string('resume_file_path')->nullable();
            $table->longText('ai_result_json')->nullable();
            $table->integer('is_suitable')->nullable();
            $table->json('recommended_roles')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('screened_at')->nullable();
            $table->timestamp('emailed_at')->nullable();
            $table->timestamps();

            // index
            $table->index('school_id');
            $table->index('ai_profile_id');
            $table->index('position_type');
            $table->index('status');
            $table->index('candidate_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_screenings');
    }
};
