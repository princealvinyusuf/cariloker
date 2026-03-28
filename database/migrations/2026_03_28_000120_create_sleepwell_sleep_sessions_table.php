<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_sleep_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->constrained('sleepwell_listeners')->cascadeOnDelete();
            $table->timestamp('started_at')->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->string('mode', 30)->default('player')->index()->comment('player|sleep_now');
            $table->string('entry_point', 50)->nullable()->comment('home_button, category_card, etc');
            $table->date('device_local_date')->nullable()->index();
            $table->string('status', 20)->default('active')->index()->comment('active|completed|abandoned');
            $table->timestamps();

            $table->index(['listener_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_sleep_sessions');
    }
};
