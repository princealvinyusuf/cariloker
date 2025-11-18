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
        Schema::table('job_imports', function (Blueprint $table) {
            // Store raw logo value from the staging source (e.g. URL or path)
            $table->text('logo')->nullable()->after('nama_perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_imports', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};


