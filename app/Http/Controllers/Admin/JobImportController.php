<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\JobsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use App\Models\Skill;

class JobImportController extends Controller
{
	public function create()
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}
		$progress = Cache::get('import:progress');
		$total = DB::table('job_imports')->count();
		return view('admin.jobs.import', ['progress' => $progress, 'total' => $total]);
	}

	public function store(Request $request)
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}

		$request->validate([
			'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
		]);

		$path = $request->file('file')->store('imports');

		$import = new JobsImport;
		$import->import(storage_path('app/'.$path));

		$successCount = $import->getSuccessCount();
		$errors = $import->getErrors();

		return Redirect::route('admin.jobs.import.create')
			->with('status', "Imported {$successCount} rows")
			->with('import_errors', $errors);
	}

	public function processStaging(Request $request)
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}

		// Allow long-running chunked processing without timeouts
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');
		DB::disableQueryLog();

		$batchSize = max(50, (int) $request->integer('batch', 200));
		$maxRows = max(100, (int) $request->integer('max', 5000));
		$total = DB::table('job_imports')->count();

		// Read existing progress (if any) from cache
		$progress = Cache::get('import:progress') ?? [];
		$processedSoFar = (int) ($progress['processed'] ?? 0);
		$lastId = (int) ($progress['last_id'] ?? 0);

		// Initialize/refresh progress metadata in cache so the UI can show something immediately
		Cache::put('import:progress', [
			'processed' => $processedSoFar,
			'last_id' => $lastId,
			'total' => $total,
			'running' => true,
		], now()->addMinutes(30));

		$processed = 0;
		$errors = [];

        DB::table('job_imports')
			->where('id', '>', $lastId)
			->orderBy('id')
            ->chunkById($batchSize, function ($rows) use (&$processed, &$errors, $maxRows, &$lastId, $total, &$processedSoFar) {
			foreach ($rows as $row) {
                    // allow external cancel (e.g., when truncate is requested)
                    if (Cache::get('import:cancel')) {
                        $errors[] = 'Processing cancelled by admin.';
                        return false; // stop chunking
                    }
				try {
					DB::transaction(function () use ($row) {
						$companyName = trim((string) ($row->nama_perusahaan ?? ''));
						$province = trim((string) ($row->provinsi ?? ''));
						$city = trim((string) ($row->kab_kota ?? ''));
						$sector = trim((string) ($row->sektor ?? ''));
						$title = trim((string) ($row->jabatan ?? ''));
						$openings = (int) ($row->jumlah_lowongan ?? 0);
						$postedAt = $this->parseDate($row->tanggal_posting ?? null);
						$validUntil = $this->parseDate($row->tanggal_berakhir ?? null);
						$url = trim((string) ($row->url ?? ''));
						$logo = trim((string) ($row->logo ?? ''));
						$gender = $this->mapGender($row->jenis_kelamin ?? null);
						$arrangement = $this->mapArrangement($row->kondisi ?? null);
						$type = $this->mapEmploymentType($row->tipe_pekerjaan ?? null);
						$level = $this->mapLevel($row->tingkat_pekerjaan ?? null);
						$education = trim((string) ($row->pendidikan ?? ''));
						list($salaryMin, $salaryMax) = $this->parseSalary((string) ($row->gaji ?? ''));
						$categoryName = trim((string) ($row->bidang_pekerjaan ?? ''));
						$skillsRaw = trim((string) ($row->keahlian ?? ''));
						$description = (string) ($row->deskripsi ?? '');
						list($expMin, $expMax) = $this->parseExperience((string) ($row->pengalaman ?? ''));

						$company = Company::firstOrCreate([
							'name' => $companyName ?: 'Perusahaan Tidak Diketahui',
						], [
							'slug' => Str::slug($companyName ?: Str::random(8)),
							'industry' => $sector ?: null,
							'logo_path' => $logo ?: null,
						]);

						// If company exists but doesn't have logo, lookup from job_imports table
						if (!$company->logo_path) {
							// First try current row logo
							if ($logo) {
								$company->logo_path = $logo;
								$company->save();
							} else {
								// If current row doesn't have logo, lookup from other job_imports rows
								$foundLogo = $this->lookupLogoFromJobImports($companyName);
								if ($foundLogo) {
									$company->logo_path = $foundLogo;
									$company->save();
								}
							}
						}

						$location = null;
						if ($province || $city) {
							$location = Location::firstOrCreate([
								'city' => $city ?: null,
								'state' => $province ?: null,
								'country' => 'ID',
							]);
						}

						$category = null;
						if ($categoryName) {
							$category = JobCategory::firstOrCreate([
								'slug' => Str::slug($categoryName),
							], [
								'name' => $categoryName,
							]);
						}

						$job = new Job();
						$job->company_id = $company->id;
						$job->category_id = $category?->id;
						$job->location_id = $location?->id;
						$job->title = $title ?: 'Tanpa Judul';
						$job->slug = Str::slug($job->title.'-'.Str::random(6));
						$job->description = $description ?: '-';
						$job->employment_type = $type ?: 'full_time';
						$job->openings = $openings ?: null;
						$job->posted_at = $postedAt;
						$job->external_url = $url ?: null;
						$job->gender = $gender ?: null;
						$job->work_arrangement = $arrangement ?: null;
						$job->seniority_level = $level ?: null;
						$job->education_level = $education ?: null;
						$job->experience_min = $expMin;
						$job->experience_max = $expMax;
						$job->salary_min = $salaryMin;
						$job->salary_max = $salaryMax;
						$job->salary_currency = ($salaryMin || $salaryMax) ? 'IDR' : null;
						$job->is_remote = ($arrangement === 'remote');
						$job->status = 'published';
						$job->valid_until = $validUntil;
						$job->save();

						if ($skillsRaw) {
							$names = collect(preg_split('/[,;|]/', $skillsRaw))->map(fn($s) => trim($s))->filter();
							$ids = [];
							foreach ($names as $sn) {
								$skill = Skill::firstOrCreate(['slug' => Str::slug($sn)], ['name' => $sn]);
								$ids[] = $skill->id;
							}
							$job->skills()->sync($ids);
						}
					});

					$processed++;
					$lastId = $row->id;
					$processedSoFar++;
					Cache::put('import:progress', [
						'processed' => $processedSoFar,
						'last_id' => $lastId,
						'total' => $total,
						'running' => true,
					], now()->addMinutes(30));
					if ($processed >= $maxRows) {
						// stop further chunk processing for this request
						return false;
					}
				} catch (\Throwable $e) {
					$errors[] = "Row {$row->id}: ".$e->getMessage();
				}
			}
			}, 'id');

		$hasMore = DB::table('job_imports')->where('id', '>', $lastId)->exists();
		Cache::put('import:progress', [
			'processed' => $processedSoFar,
			'last_id' => $lastId,
			'total' => $total,
			'running' => $hasMore,
		], now()->addMinutes(30));
        // clear cancel flag if set
        if (!$hasMore) {
            Cache::forget('import:cancel');
        }

		return Redirect::route('admin.jobs.import.create')
			->with('status', "Processed {$processed} staging rows")
			->with('import_errors', $errors);
	}

	public function truncateAll(Request $request)
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}

        // Signal any in-flight processing to stop
        Cache::put('import:cancel', true, now()->addMinutes(10));

		DB::beginTransaction();
		try {
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
			// Truncate in safe order (children first)
			foreach ([
				'job_listing_skill',
				'applications',
				'saved_jobs',
				'job_listings',
				'companies',
				'locations',
				'job_categories',
				'skills',
			] as $table) {
				if (Schema::hasTable($table)) {
					DB::table($table)->truncate();
				}
			}
			DB::statement('SET FOREIGN_KEY_CHECKS=1');
			DB::commit();
            Cache::forget('import:progress');
            Cache::forget('import:cancel');
			return Redirect::route('admin.jobs.import.create')->with('status', 'All related tables truncated.');
		} catch (\Throwable $e) {
			DB::rollBack();
			return Redirect::route('admin.jobs.import.create')->with('import_errors', [$e->getMessage()]);
		}
	}

	private function parseDate($value): ?string
	{
		if (!$value) return null;
		$value = trim((string) $value);
		foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $fmt) {
			$dt = \DateTime::createFromFormat($fmt, $value);
			if ($dt) return $dt->format('Y-m-d');
		}
		if (is_numeric($value)) {
			return date('Y-m-d', (int) $value);
		}
		return null;
	}

	private function mapGender($value): ?string
	{
		$value = strtolower((string) $value);
		return match(true) {
			str_contains($value, 'pria') || str_contains($value, 'male') => 'male',
			str_contains($value, 'wanita') || str_contains($value, 'female') => 'female',
			default => null,
		};
	}

	private function mapArrangement($value): ?string
	{
		$value = strtolower((string) $value);
		return match(true) {
			str_contains($value, 'kantor') || str_contains($value, 'onsite') => 'onsite',
			str_contains($value, 'hybrid') => 'hybrid',
			str_contains($value, 'remote') => 'remote',
			default => null,
		};
	}

	private function mapEmploymentType($value): ?string
	{
		$value = strtolower((string) $value);
		return match(true) {
			str_contains($value, 'purna') || str_contains($value, 'full') => 'full_time',
			str_contains($value, 'paruh') || str_contains($value, 'part') => 'part_time',
			str_contains($value, 'kontrak') || str_contains($value, 'contract') => 'contract',
			str_contains($value, 'magang') || str_contains($value, 'intern') => 'internship',
			str_contains($value, 'lepas') || str_contains($value, 'freelance') => 'freelance',
			default => null,
		};
	}

	private function mapLevel($value): ?string
	{
		$value = strtolower((string) $value);
		return match(true) {
			str_contains($value, 'entry') => 'entry',
			str_contains($value, 'mid') || str_contains($value, 'middle') => 'mid',
			str_contains($value, 'senior') => 'senior',
			str_contains($value, 'lead') => 'lead',
			default => null,
		};
	}

	private function parseSalary(string $raw): array
	{
		if ($raw === '') return [null, null];
		preg_match_all('/\d[\d\.,]*/', $raw, $m);
		$nums = array_map(fn($n) => (int) str_replace([',','.'], '', $n), $m[0]);
		if (count($nums) >= 2) return [$nums[0], $nums[1]];
		if (count($nums) === 1) return [$nums[0], null];
		return [null, null];
	}

	private function parseExperience(string $raw): array
	{
		if ($raw === '') return [null, null];
		preg_match_all('/\d+/', $raw, $m);
		if (!$m[0]) return [null, null];
		$nums = array_map('intval', $m[0]);
		if (count($nums) >= 2) return [$nums[0], $nums[1]];
		return [$nums[0], $nums[0]];
	}

	/**
	 * Lookup logo from job_imports table for a given company name
	 * Returns the first non-empty logo found for the company
	 */
	private function lookupLogoFromJobImports(string $companyName): ?string
	{
		if (empty($companyName)) {
			return null;
		}

		$logo = DB::table('job_imports')
			->where('nama_perusahaan', $companyName)
			->whereNotNull('logo')
			->where('logo', '!=', '')
			->value('logo');

		return $logo ? trim((string) $logo) : null;
	}
}


