<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_tracked_night_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_night_id')->constrained('sleepwell_tracked_nights')->cascadeOnDelete();
            $table->string('detection_key', 40)->index();
            $table->string('label', 80);
            $table->unsignedTinyInteger('occurrence_count')->default(0);
            $table->unsignedInteger('total_seconds')->default(0);
            $table->unsignedTinyInteger('confidence_score')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['tracked_night_id', 'detection_key'], 'sleepwell_tracked_night_detection_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_tracked_night_detections');
    }
};
