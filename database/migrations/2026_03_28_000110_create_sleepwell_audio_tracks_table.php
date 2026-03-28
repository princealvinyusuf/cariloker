<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_audio_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->index();
            $table->string('sound_type', 50)->nullable()->index();
            $table->unsignedInteger('duration_seconds')->default(1800);
            $table->boolean('talking')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('stream_url', 500);
            $table->string('cover_image_url', 500)->nullable();
            $table->timestamps();
        });

        DB::table('sleepwell_audio_tracks')->insert([
            [
                'title' => 'Soft Rain on Window',
                'category' => 'rain',
                'sound_type' => 'rain',
                'duration_seconds' => 3600,
                'talking' => false,
                'is_active' => true,
                'stream_url' => 'https://cdn.sleepwell.app/audio/rain-window.mp3',
                'cover_image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gentle Whisper Story',
                'category' => 'whisper',
                'sound_type' => 'story',
                'duration_seconds' => 2400,
                'talking' => true,
                'is_active' => true,
                'stream_url' => 'https://cdn.sleepwell.app/audio/whisper-story.mp3',
                'cover_image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'No Talking Brown Noise',
                'category' => 'no_talking',
                'sound_type' => 'brown_noise',
                'duration_seconds' => 4800,
                'talking' => false,
                'is_active' => true,
                'stream_url' => 'https://cdn.sleepwell.app/audio/brown-noise.mp3',
                'cover_image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Spa Roleplay Night Routine',
                'category' => 'roleplay',
                'sound_type' => 'roleplay',
                'duration_seconds' => 2100,
                'talking' => true,
                'is_active' => true,
                'stream_url' => 'https://cdn.sleepwell.app/audio/spa-roleplay.mp3',
                'cover_image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_audio_tracks');
    }
};
