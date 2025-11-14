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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('status_code'); // HTTP status code (404, 500, etc.)
            $table->string('method', 10)->nullable(); // GET, POST, etc.
            $table->string('url')->nullable(); // The URL where error occurred
            $table->string('route')->nullable(); // Route name if available
            $table->text('message')->nullable(); // Error message
            $table->string('ip_address', 45)->nullable(); // Visitor IP
            $table->text('user_agent')->nullable(); // User agent
            $table->unsignedInteger('count')->default(1); // Count of same error
            $table->timestamp('first_occurred_at')->useCurrent();
            $table->timestamp('last_occurred_at')->useCurrent();
            $table->timestamps();
            
            $table->index('status_code');
            $table->index('url');
            $table->index('last_occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
