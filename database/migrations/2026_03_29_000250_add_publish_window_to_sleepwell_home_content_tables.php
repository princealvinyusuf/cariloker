<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sleepwell_home_sections', function (Blueprint $table) {
            $table->timestamp('publish_at')->nullable()->after('is_active');
            $table->timestamp('unpublish_at')->nullable()->after('publish_at');
            $table->index(['publish_at', 'unpublish_at']);
        });

        Schema::table('sleepwell_home_items', function (Blueprint $table) {
            $table->timestamp('publish_at')->nullable()->after('is_active');
            $table->timestamp('unpublish_at')->nullable()->after('publish_at');
            $table->index(['publish_at', 'unpublish_at']);
        });
    }

    public function down(): void
    {
        Schema::table('sleepwell_home_items', function (Blueprint $table) {
            $table->dropIndex(['publish_at', 'unpublish_at']);
            $table->dropColumn(['publish_at', 'unpublish_at']);
        });

        Schema::table('sleepwell_home_sections', function (Blueprint $table) {
            $table->dropIndex(['publish_at', 'unpublish_at']);
            $table->dropColumn(['publish_at', 'unpublish_at']);
        });
    }
};
