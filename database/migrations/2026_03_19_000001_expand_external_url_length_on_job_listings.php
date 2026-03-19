<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN external_url TEXT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE job_listings ALTER COLUMN external_url TYPE TEXT');
            return;
        }

        if ($driver === 'sqlite') {
            // SQLite does not enforce VARCHAR length, so no change is required.
            return;
        }

        Schema::table('job_listings', function (Blueprint $table) {
            $table->text('external_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN external_url VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE job_listings ALTER COLUMN external_url TYPE VARCHAR(255)');
            return;
        }

        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('job_listings', function (Blueprint $table) {
            $table->string('external_url', 255)->nullable()->change();
        });
    }
};
