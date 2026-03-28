<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_home_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sleepwell_home_sections')->cascadeOnDelete();
            $table->string('title', 180);
            $table->string('subtitle', 300)->nullable();
            $table->string('tag', 80)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('icon_url', 500)->nullable();
            $table->string('cta_label', 40)->nullable();
            $table->foreignId('audio_track_id')->nullable()->constrained('sleepwell_audio_tracks')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_home_items');
    }
};
