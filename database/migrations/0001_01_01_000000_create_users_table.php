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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->enum('gender', ['male', 'female', 'others'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('otp')->nullable();
            $table->boolean('is_otp_verified')->default(false);
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('reset_password_token')->nullable();
            $table->timestamp('reset_password_token_expire_at')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->rememberToken();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
