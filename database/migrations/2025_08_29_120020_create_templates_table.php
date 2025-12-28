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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Name of the template');
            $table->string('suject')->nullable()->comment('Subject of the email');
            $table->text('content')->nullable()->comment('Content of the email');
            $table->integer('type')->comment('Type of the template (birthday, normal)');
            $table->integer('object_type')->comment('Object type (customer, user)');
            $table->integer('status')->default(0)->comment('Status of the template (manual, auto)');
            $table->time('send_time')->nullable()->comment('Time when the email should be sent');
            $table->date('send_date')->nullable()->comment('Date when the email should be sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
