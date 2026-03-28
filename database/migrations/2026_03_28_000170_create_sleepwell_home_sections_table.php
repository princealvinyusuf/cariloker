<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 80)->unique();
            $table->string('title', 160)->nullable();
            $table->string('subtitle', 300)->nullable();
            $table->string('section_type', 40)->index()->comment('hero_carousel|grid|horizontal|top_ranked|chips|promo');
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_home_sections');
    }
};
