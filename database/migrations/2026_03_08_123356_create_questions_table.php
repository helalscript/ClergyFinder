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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable()->comment('clergy|seeker');
            $table->string('question')->nullable();
            $table->string('image')->nullable();
            $table->string('answer_type')->nullable()->comment('text|textarea|single_choice|multiple_choice|yes_no');
            $table->longText('answer')->nullable()->comment('Admin reference answer');
            $table->unsignedInteger('sort_order')->nullable()->default(0);
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
