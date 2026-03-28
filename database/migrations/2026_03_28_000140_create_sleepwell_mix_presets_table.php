<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_mix_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->constrained('sleepwell_listeners')->cascadeOnDelete();
            $table->string('name', 80);
            $table->json('channels')->comment('Example: {"rain":0.8,"wind":0.4}');
            $table->timestamps();

            $table->index(['listener_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_mix_presets');
    }
};
