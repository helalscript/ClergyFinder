<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_session_requests', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('agora_channel');
        });
    }

    public function down(): void
    {
        Schema::table('live_session_requests', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
