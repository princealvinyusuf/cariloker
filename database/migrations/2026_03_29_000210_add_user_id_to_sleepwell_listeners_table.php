<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sleepwell_listeners', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::table('sleepwell_listeners', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
