<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('full_name')->nullable();
            $table->string('file')->nullable();
            $table->string('email')->nullable();
            $table->longText('message')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_agree')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
