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
            // MySQL: Direct ALTER TABLE
            DB::statement('ALTER TABLE job_imports MODIFY COLUMN nama_perusahaan TEXT NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite: Requires table recreation
            DB::statement('ALTER TABLE job_imports RENAME TO job_imports_old');
            
            Schema::create('job_imports', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('nama_perusahaan')->nullable();
                $table->string('provinsi')->nullable();
                $table->string('kab_kota')->nullable();
                $table->string('sektor')->nullable();
                $table->string('jabatan')->nullable();
                $table->unsignedInteger('jumlah_lowongan')->nullable();
                $table->string('tanggal_posting')->nullable();
                $table->string('tanggal_berakhir')->nullable();
                $table->text('url')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->string('kondisi')->nullable();
                $table->string('tipe_pekerjaan')->nullable();
                $table->string('tingkat_pekerjaan')->nullable();
                $table->string('pendidikan')->nullable();
                $table->string('gaji')->nullable();
                $table->string('bidang_pekerjaan')->nullable();
                $table->text('keahlian')->nullable();
                $table->longText('deskripsi')->nullable();
                $table->string('pengalaman')->nullable();
                $table->timestamps();
            });
            
            DB::statement('INSERT INTO job_imports SELECT * FROM job_imports_old');
            DB::statement('DROP TABLE job_imports_old');
        } else {
            // PostgreSQL and others
            Schema::table('job_imports', function (Blueprint $table) {
                $table->text('nama_perusahaan')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to VARCHAR(1000)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE job_imports MODIFY COLUMN nama_perusahaan VARCHAR(1000) NULL');
        } else {
            Schema::table('job_imports', function (Blueprint $table) {
                $table->string('nama_perusahaan', 1000)->nullable()->change();
            });
        }
    }
};
