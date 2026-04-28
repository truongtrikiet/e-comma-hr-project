<?php

use App\Enum\SalaryStatus;
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
        Schema::create('salary_proposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->decimal('proposed_gross_amount', 16, 4)->nullable();
            $table->decimal('proposed_tax_percent', 5, 2)->nullable();
            $table->decimal('proposed_tax_amount', 16, 4)->nullable();
            $table->decimal('proposed_net_amount', 16, 4)->nullable();
            $table->boolean('is_applied')->default(false);
            $table->text('reason')->nullable();
            $table->date('ends_at')->nullable();
            $table->date('effective_date')->nullable();
            $table->integer('status')->default(SalaryStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_proposes');
    }
};
