<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            $table->string('type', 30); // prayer | live_session

            $table->foreignId('prayer_request_id')
                ->nullable()
                ->unique()
                ->constrained('prayer_requests')
                ->nullOnDelete();

            $table->foreignId('live_session_request_id')
                ->nullable()
                ->unique()
                ->constrained('live_session_requests')
                ->nullOnDelete();

            $table->foreignId('seeker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clergy_id')->constrained('users')->cascadeOnDelete();

            $table->string('status', 30)->default('active'); // active | completed

            // Only meaningful for live_session rooms.
            $table->string('video_status', 30)->default('not_started'); // not_started | started | completed
            $table->timestamp('video_started_at')->nullable();
            $table->timestamp('video_completed_at')->nullable();

            $table->timestamps();

            $table->index(['seeker_id']);
            $table->index(['clergy_id']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};

