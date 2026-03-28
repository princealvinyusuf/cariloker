<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_onboarding_screens', function (Blueprint $table) {
            $table->id();
            $table->string('step_key', 80)->index();
            $table->string('screen_type', 40)->index()->comment('welcome|single_choice|multi_choice|slider|info|email');
            $table->string('title', 255);
            $table->string('subtitle', 500)->nullable();
            $table->json('options')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('cta_label', 40)->default('Continue');
            $table->boolean('skippable')->default(true);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_onboarding_screens');
    }
};
