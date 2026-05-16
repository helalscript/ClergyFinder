<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clergies', function (Blueprint $table) {
            if (! Schema::hasColumn('clergies', 'registration_status')) {
                $table->string('registration_status', 30)->default('approved')->after('status');
            }
            if (! Schema::hasColumn('clergies', 'denomination')) {
                $table->string('denomination')->nullable()->after('religion');
            }
            if (! Schema::hasColumn('clergies', 'credential_documents')) {
                $table->json('credential_documents')->nullable()->after('bio');
            }
            if (! Schema::hasColumn('clergies', 'registration_rejection_reason')) {
                $table->text('registration_rejection_reason')->nullable()->after('credential_documents');
            }
            if (! Schema::hasColumn('clergies', 'registration_reviewed_at')) {
                $table->timestamp('registration_reviewed_at')->nullable()->after('registration_rejection_reason');
            }
            if (! Schema::hasColumn('clergies', 'is_available_for_live')) {
                $table->boolean('is_available_for_live')->default(false)->after('is_live_session');
            }
            if (! Schema::hasColumn('clergies', 'live_unavailable_message')) {
                $table->text('live_unavailable_message')->nullable()->after('is_available_for_live');
            }
        });

        Schema::table('prayer_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('prayer_requests', 'declined_reason')) {
                $table->text('declined_reason')->nullable()->after('clergy_reply');
            }
            if (! Schema::hasColumn('prayer_requests', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('status');
            }
        });

        // Normalize prayer request statuses
        if (Schema::hasTable('prayer_requests')) {
            DB::table('prayer_requests')->where('status', 'pending')->update(['status' => 'pending_clergy']);
            DB::table('prayer_requests')->where('status', 'replied')->update(['status' => 'active']);
        }

        Schema::table('live_session_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('live_session_requests', 'queue_joined_at')) {
                $table->timestamp('queue_joined_at')->nullable()->after('message');
            }
            if (! Schema::hasColumn('live_session_requests', 'last_queue_heartbeat_at')) {
                $table->timestamp('last_queue_heartbeat_at')->nullable()->after('queue_joined_at');
            }
            if (! Schema::hasColumn('live_session_requests', 'confirmation_deadline_at')) {
                $table->timestamp('confirmation_deadline_at')->nullable()->after('last_queue_heartbeat_at');
            }
            if (! Schema::hasColumn('live_session_requests', 'ready_confirmed_at')) {
                $table->timestamp('ready_confirmed_at')->nullable()->after('confirmation_deadline_at');
            }
            if (! Schema::hasColumn('live_session_requests', 'agora_channel')) {
                $table->string('agora_channel')->nullable()->after('ready_confirmed_at');
            }
        });

        if (Schema::hasTable('live_session_requests')) {
            DB::table('live_session_requests')->where('status', 'pending')->update(['status' => 'cancelled']);
            DB::table('live_session_requests')->where('status', 'scheduled')->update(['status' => 'completed']);
        }
    }

    public function down(): void
    {
        Schema::table('live_session_requests', function (Blueprint $table) {
            foreach (['agora_channel', 'ready_confirmed_at', 'confirmation_deadline_at', 'last_queue_heartbeat_at', 'queue_joined_at'] as $col) {
                if (Schema::hasColumn('live_session_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('prayer_requests', function (Blueprint $table) {
            if (Schema::hasColumn('prayer_requests', 'closed_at')) {
                $table->dropColumn('closed_at');
            }
            if (Schema::hasColumn('prayer_requests', 'declined_reason')) {
                $table->dropColumn('declined_reason');
            }
        });

        Schema::table('clergies', function (Blueprint $table) {
            foreach (['live_unavailable_message', 'is_available_for_live', 'registration_reviewed_at', 'registration_rejection_reason', 'credential_documents', 'denomination', 'registration_status'] as $col) {
                if (Schema::hasColumn('clergies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
