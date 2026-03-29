<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_tracked_nights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->constrained('sleepwell_listeners')->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('sleepwell_sleep_sessions')->nullOnDelete();
            $table->foreignId('preferred_track_id')->nullable()->constrained('sleepwell_audio_tracks')->nullOnDelete();
            $table->timestamp('started_at')->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->date('tracked_date')->nullable()->index();
            $table->string('entry_point', 60)->nullable();
            $table->string('status', 30)->default('active')->index()->comment('active|uploaded|completed|analyzed');
            $table->unsignedSmallInteger('sleep_goal_minutes')->default(480);
            $table->unsignedTinyInteger('smart_alarm_window_minutes')->default(30);
            $table->string('wake_alarm_time', 20)->nullable();
            $table->string('recording_path')->nullable();
            $table->unsignedInteger('recording_duration_seconds')->default(0);
            $table->timestamp('recording_uploaded_at')->nullable();
            $table->timestamp('last_analyzed_at')->nullable();
            $table->json('mix_snapshot')->nullable();
            $table->json('metadata')->nullable();
            $table->json('insights_payload')->nullable();
            $table->timestamps();

            $table->index(['listener_id', 'tracked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_tracked_nights');
    }
};
