<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitor_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique(); // IPv6 can be up to 45 characters
            $table->timestamp('first_visited_at')->useCurrent();
            $table->timestamp('last_visited_at')->useCurrent();
            $table->unsignedInteger('visit_count')->default(1);
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('last_visited_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_ips');
    }
};
