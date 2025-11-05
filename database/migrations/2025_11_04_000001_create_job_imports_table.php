<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('job_imports', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nama_perusahaan', 1000)->nullable();
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
	}

	public function down(): void
	{
		Schema::dropIfExists('job_imports');
	}
};


