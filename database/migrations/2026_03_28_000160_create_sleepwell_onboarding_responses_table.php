<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_onboarding_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->constrained('sleepwell_listeners')->cascadeOnDelete();
            $table->json('answers');
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();

            $table->index(['listener_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_onboarding_responses');
    }
};
