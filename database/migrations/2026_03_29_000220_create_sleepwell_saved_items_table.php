<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleepwell_saved_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('item_type', 40);
            $table->string('item_ref', 180);
            $table->string('title', 180);
            $table->string('subtitle', 300)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('last_played_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'item_type', 'item_ref'], 'sleepwell_saved_user_item_unique');
            $table->index(['user_id', 'item_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleepwell_saved_items');
    }
};
