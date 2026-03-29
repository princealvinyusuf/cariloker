<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_tracked_night_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_night_id')->constrained('sleepwell_tracked_nights')->cascadeOnDelete();
            $table->string('label', 80);
            $table->string('detection_key', 40)->nullable()->index();
            $table->unsignedInteger('start_second')->default(0);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedTinyInteger('confidence_score')->default(0);
            $table->string('source_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tracked_night_id', 'start_second']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_tracked_night_recordings');
    }
};
