<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = 'job_imports';
        $legacyColumns = [
            'logo_path',
            'gaji, type=text, pos=17',
            'provinsi, type=text, pos=4',
        ];

        foreach ($legacyColumns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                continue;
            }
            $this->dropColumnByDriver($table, $column);
        }
    }

    public function down(): void
    {
        // Intentionally no-op: these are legacy/accidental columns.
    }

    private function dropColumnByDriver(string $table, string $column): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement(sprintf(
                'ALTER TABLE `%s` DROP COLUMN `%s`',
                str_replace('`', '``', $table),
                str_replace('`', '``', $column)
            ));
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement(sprintf(
                'ALTER TABLE "%s" DROP COLUMN "%s"',
                str_replace('"', '""', $table),
                str_replace('"', '""', $column)
            ));
            return;
        }

        // SQLite and other drivers are ignored for this legacy cleanup.
    }
};
