<?php

use App\Enum\ActiveStatus;
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
        Schema::create('furlough_policy_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('accrual_per_month')->nullable();
            $table->integer('max_days')->nullable();
            $table->boolean('carry_forward')->default(true);
            $table->integer('is_paid')->default(IsPaid::PAID->value);
            $table->integer('status')->default(ActiveStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furlough_policy_templates');
    }
};
