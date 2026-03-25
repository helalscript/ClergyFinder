<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'faq_category_id')) {
                $table->foreignId('faq_category_id')->nullable()->after('id')->constrained('faq_categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'faq_category_id')) {
                $table->dropConstrainedForeignId('faq_category_id');
                $table->dropColumn('faq_category_id');
            }
        });
    }
};

