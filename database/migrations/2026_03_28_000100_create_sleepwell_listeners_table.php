<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_listeners', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 120)->unique();
            $table->string('timezone', 80)->nullable();
            $table->unsignedTinyInteger('sleep_difficulty')->nullable()->comment('1-5');
            $table->boolean('prefers_talking')->nullable();
            $table->json('preferred_categories')->nullable();
            $table->json('preferred_sound_types')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            $table->index('last_active_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_listeners');
    }
};
