<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('job_listings', function (Blueprint $table) {
			$table->unsignedSmallInteger('openings')->nullable()->after('employment_type');
			$table->timestamp('posted_at')->nullable()->after('openings');
			$table->string('external_url')->nullable()->after('posted_at');
			$table->string('gender')->nullable()->after('external_url'); // any, male, female
			$table->string('work_arrangement')->nullable()->after('gender'); // onsite, hybrid, remote
			$table->string('seniority_level')->nullable()->after('work_arrangement'); // intern, junior, mid, senior, lead
			$table->string('education_level')->nullable()->after('seniority_level');
		});
	}

	public function down(): void
	{
		Schema::table('job_listings', function (Blueprint $table) {
			$table->dropColumn([
				'openings',
				'posted_at',
				'external_url',
				'gender',
				'work_arrangement',
				'seniority_level',
				'education_level',
			]);
		});
	}
};


