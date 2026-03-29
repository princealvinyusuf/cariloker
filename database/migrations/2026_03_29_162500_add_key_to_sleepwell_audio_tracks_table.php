<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sleepwell_audio_tracks', function (Blueprint $table) {
            $table->string('key', 80)->nullable()->after('subtitle')->index();
        });
    }

    public function down(): void
    {
        Schema::table('sleepwell_audio_tracks', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
};
