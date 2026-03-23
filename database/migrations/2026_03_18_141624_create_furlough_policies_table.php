<?php

use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use App\Enum\ResetTypeEnum;
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
        Schema::create('furlough_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->foreignId('employee_type_id')
                ->nullable()
                ->constrained('employee_types')
                ->cascadeOnDelete();
            $table->foreignId('furlough_type_id')
                ->nullable()
                ->constrained('furlough_types')
                ->cascadeOnDelete();
            $table->foreignId('furlough_policy_template_id')
                ->nullable()
                ->constrained('furlough_policy_templates')
                ->cascadeOnDelete();
            $table->integer('accrual_per_month')->nullable();
            $table->integer('max_days')->nullable();
            $table->boolean('carry_forward')->default(false);
            $table->integer('is_paid')->default(IsPaid::PAID->value);
            $table->integer('reset_type')->default(ResetTypeEnum::NONE->value);
            $table->integer('reset_month')->nullable();
            $table->integer('status')->default(ActiveStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furlough_policies');
    }
};
