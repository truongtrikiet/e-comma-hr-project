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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('birth')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('identification_number')->nullable();
            $table->date('identification_date')->nullable();
            $table->string('identification_place')->nullable();
            $table->date('company_entry_date')->nullable();
            $table->string('education_level')->nullable();
            $table->string('gender')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('personal_income_tax')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('relative_name')->nullable();
            $table->string('relative_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
