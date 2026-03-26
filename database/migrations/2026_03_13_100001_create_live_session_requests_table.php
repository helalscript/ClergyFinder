<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_session_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clergy_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message');
            $table->string('meeting_link')->nullable();
            $table->dateTime('scheduled_time')->nullable();
            $table->string('status', 20)->default('pending'); // pending, scheduled, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_session_requests');
    }
};
