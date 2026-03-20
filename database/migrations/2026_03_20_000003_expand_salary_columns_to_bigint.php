<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN salary_min BIGINT NULL');
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN salary_max BIGINT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE job_listings ALTER COLUMN salary_min TYPE BIGINT');
            DB::statement('ALTER TABLE job_listings ALTER COLUMN salary_max TYPE BIGINT');
            return;
        }

        // SQLite/others: keep as-is (SQLite is dynamically typed).
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN salary_min INT NULL');
            DB::statement('ALTER TABLE job_listings MODIFY COLUMN salary_max INT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE job_listings ALTER COLUMN salary_min TYPE INTEGER');
            DB::statement('ALTER TABLE job_listings ALTER COLUMN salary_max TYPE INTEGER');
            return;
        }

        // SQLite/others: keep as-is.
    }
};
