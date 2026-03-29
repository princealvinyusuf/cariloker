<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('screen', 40);
            $table->string('slot_key', 60);
            $table->string('format', 30)->default('banner');
            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('frequency_cap')->default(0);
            $table->string('countries', 200)->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('ad_unit_id_android', 255)->nullable();
            $table->string('ad_unit_id_ios', 255)->nullable();
            $table->timestamps();
            $table->unique(['screen', 'slot_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_ad_placements');
    }
};
