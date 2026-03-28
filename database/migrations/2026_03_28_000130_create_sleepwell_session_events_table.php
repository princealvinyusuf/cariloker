<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_session_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('sleepwell_sleep_sessions')->cascadeOnDelete();
            $table->foreignId('track_id')->nullable()->constrained('sleepwell_audio_tracks')->nullOnDelete();
            $table->string('event_type', 50)->index()->comment('play, pause, skip, repeat, timer_set, completed');
            $table->timestamp('event_at')->index();
            $table->unsignedInteger('position_seconds')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['session_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_session_events');
    }
};
