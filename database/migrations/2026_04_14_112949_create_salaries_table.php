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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('gross_amount', 16, 4)->nullable();
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('tax_amount', 16, 4)->nullable();
            $table->decimal('net_amount', 16, 4)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('effective_date')->nullable();
            $table->integer('status')->default(SalaryStatus::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
