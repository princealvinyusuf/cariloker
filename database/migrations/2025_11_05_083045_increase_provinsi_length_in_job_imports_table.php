<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL: Change multiple string columns to TEXT to prevent truncation errors
            $columns = [
                'provinsi',
                'kab_kota',
                'sektor',
                'jabatan',
                'bidang_pekerjaan',
                'pendidikan',
                'gaji',
                'pengalaman',
                'jenis_kelamin',
                'kondisi',
                'tipe_pekerjaan',
                'tingkat_pekerjaan',
            ];
            
            foreach ($columns as $column) {
                DB::statement("ALTER TABLE job_imports MODIFY COLUMN {$column} TEXT NULL");
            }
        } elseif ($driver === 'sqlite') {
            // SQLite: Requires table recreation
            DB::statement('ALTER TABLE job_imports RENAME TO job_imports_old');
            
            Schema::create('job_imports', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('nama_perusahaan')->nullable();
                $table->text('provinsi')->nullable();
                $table->text('kab_kota')->nullable();
                $table->text('sektor')->nullable();
                $table->text('jabatan')->nullable();
                $table->unsignedInteger('jumlah_lowongan')->nullable();
                $table->string('tanggal_posting')->nullable();
                $table->string('tanggal_berakhir')->nullable();
                $table->text('url')->nullable();
                $table->text('jenis_kelamin')->nullable();
                $table->text('kondisi')->nullable();
                $table->text('tipe_pekerjaan')->nullable();
                $table->text('tingkat_pekerjaan')->nullable();
                $table->text('pendidikan')->nullable();
                $table->text('gaji')->nullable();
                $table->text('bidang_pekerjaan')->nullable();
                $table->text('keahlian')->nullable();
                $table->longText('deskripsi')->nullable();
                $table->text('pengalaman')->nullable();
                $table->timestamps();
            });
            
            DB::statement('INSERT INTO job_imports SELECT * FROM job_imports_old');
            DB::statement('DROP TABLE job_imports_old');
        } else {
            // PostgreSQL and others
            Schema::table('job_imports', function (Blueprint $table) {
                $table->text('provinsi')->nullable()->change();
                $table->text('kab_kota')->nullable()->change();
                $table->text('sektor')->nullable()->change();
                $table->text('jabatan')->nullable()->change();
                $table->text('bidang_pekerjaan')->nullable()->change();
                $table->text('pendidikan')->nullable()->change();
                $table->text('gaji')->nullable()->change();
                $table->text('pengalaman')->nullable()->change();
                $table->text('jenis_kelamin')->nullable()->change();
                $table->text('kondisi')->nullable()->change();
                $table->text('tipe_pekerjaan')->nullable()->change();
                $table->text('tingkat_pekerjaan')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // Revert to VARCHAR(255)
            $columns = [
                'provinsi',
                'kab_kota',
                'sektor',
                'jabatan',
                'bidang_pekerjaan',
                'pendidikan',
                'gaji',
                'pengalaman',
                'jenis_kelamin',
                'kondisi',
                'tipe_pekerjaan',
                'tingkat_pekerjaan',
            ];
            
            foreach ($columns as $column) {
                DB::statement("ALTER TABLE job_imports MODIFY COLUMN {$column} VARCHAR(255) NULL");
            }
        } else {
            Schema::table('job_imports', function (Blueprint $table) {
                $table->string('provinsi')->nullable()->change();
                $table->string('kab_kota')->nullable()->change();
                $table->string('sektor')->nullable()->change();
                $table->string('jabatan')->nullable()->change();
                $table->string('bidang_pekerjaan')->nullable()->change();
                $table->string('pendidikan')->nullable()->change();
                $table->string('gaji')->nullable()->change();
                $table->string('pengalaman')->nullable()->change();
                $table->string('jenis_kelamin')->nullable()->change();
                $table->string('kondisi')->nullable()->change();
                $table->string('tipe_pekerjaan')->nullable()->change();
                $table->string('tingkat_pekerjaan')->nullable()->change();
            });
        }
    }
};
