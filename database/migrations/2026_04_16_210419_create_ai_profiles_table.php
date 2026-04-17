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
        Schema::create('ai_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->string('provider')->nullable();
            $table->longText('api_key_encrypted')->nullable();
            $table->string('model')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('status')->default(ActiveStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_profiles');
    }
};
