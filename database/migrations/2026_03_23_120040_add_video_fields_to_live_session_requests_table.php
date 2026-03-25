<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_session_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('live_session_requests', 'video_status')) {
                $table->string('video_status', 30)->default('not_started')->after('status');
            }

            if (!Schema::hasColumn('live_session_requests', 'video_started_at')) {
                $table->timestamp('video_started_at')->nullable()->after('video_status');
            }

            if (!Schema::hasColumn('live_session_requests', 'video_completed_at')) {
                $table->timestamp('video_completed_at')->nullable()->after('video_started_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('live_session_requests', function (Blueprint $table) {
            if (Schema::hasColumn('live_session_requests', 'video_completed_at')) {
                $table->dropColumn('video_completed_at');
            }
            if (Schema::hasColumn('live_session_requests', 'video_started_at')) {
                $table->dropColumn('video_started_at');
            }
            if (Schema::hasColumn('live_session_requests', 'video_status')) {
                $table->dropColumn('video_status');
            }
        });
    }
};

