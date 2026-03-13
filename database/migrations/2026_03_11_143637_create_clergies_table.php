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
        Schema::create('clergies', function (Blueprint $table) {
            $table->id();
            $table->string('religion')->nullable();
            $table->json('preferred_language')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->longText('bio')->nullable();
            $table->string('available_at')->default('Available Sunday-Thursday');
            $table->json('specialities')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_live_session')->default(true);
            $table->boolean('is_prayer_request')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clergies');
    }
};
