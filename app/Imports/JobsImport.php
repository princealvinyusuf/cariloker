<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobsImport implements ToCollection, WithHeadingRow
{
	private int $successCount = 0;
	private array $errors = [];

	public function collection(Collection $rows)
	{
		foreach ($rows as $index => $row) {
			try {
				DB::transaction(function () use ($row) {
					$companyName = trim((string) ($row['nama_perusahaan'] ?? $row['nama perusahaan'] ?? ''));
					$province = trim((string) ($row['provinsi'] ?? ''));
					$city = trim((string) ($row['kab/kota'] ?? $row['kab_kota'] ?? ''));
					$sector = trim((string) ($row['sektor'] ?? ''));
					$title = trim((string) ($row['jabatan'] ?? ''));
					$openings = (int) ($row['jumlah_lowongan_(orang)'] ?? $row['jumlah lowongan (orang)'] ?? 1);
					$postedAt = $this->parseDate($row['tanggal_posting'] ?? $row['tanggal posting'] ?? null);
					$validUntil = $this->parseDate($row['tanggal_berakhir'] ?? $row['tanggal berakhir'] ?? null);
					$url = trim((string) ($row['url'] ?? ''));
					$logo = trim((string) ($row['logo'] ?? ''));
					$gender = $this->mapGender($row['jenis_kelamin'] ?? $row['jenis kelamin'] ?? null);
					$arrangement = $this->mapArrangement($row['kondisi'] ?? null);
					$type = $this->mapEmploymentType($row['tipe_pekerjaan'] ?? $row['tipe pekerjaan'] ?? null);
					$level = $this->mapLevel($row['tingkat_pekerjaan'] ?? $row['tingkat pekerjaan'] ?? null);
					$education = trim((string) ($row['pendidikan'] ?? ''));
					$salaryRaw = trim((string) ($row['gaji'] ?? ''));
					list($salaryMin, $salaryMax) = $this->parseSalary($salaryRaw);
					$categoryName = trim((string) ($row['bidang_pekerjaan'] ?? $row['bidang pekerjaan'] ?? ''));
					$skillsRaw = trim((string) ($row['keahlian'] ?? ''));
					$description = (string) ($row['deskripsi'] ?? '');
					$experienceRaw = trim((string) ($row['pengalaman'] ?? ''));
					list($expMin, $expMax) = $this->parseExperience($experienceRaw);

					$company = Company::firstOrCreate([
						'name' => $companyName ?: 'Perusahaan Tidak Diketahui',
					], [
						'slug' => Str::slug($companyName ?: Str::random(8)),
						'industry' => $sector ?: null,
						'logo_path' => $logo ?: null,
					]);

					// If company exists but doesn't have logo, and we have one, update it
					if (!$company->logo_path && $logo) {
						$company->logo_path = $logo;
						$company->save();
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
						$skillNames = collect(preg_split('/[,;|]/', $skillsRaw))
							->map(fn($s) => trim($s))
							->filter();
						$skillIds = [];
						foreach ($skillNames as $sn) {
							$skill = Skill::firstOrCreate(['slug' => Str::slug($sn)], ['name' => $sn]);
							$skillIds[] = $skill->id;
						}
						$job->skills()->sync($skillIds);
					}
				});

				$this->successCount++;
			} catch (\Throwable $e) {
				$this->errors[] = $e->getMessage();
			}
		}
	}

	public function getSuccessCount(): int
	{
		return $this->successCount;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	private function parseDate($value): ?string
	{
		if (!$value) return null;
		$value = trim((string) $value);
		// formats like 03/09/2025 or 03-09-2025
		foreach (['d/m/Y', 'd-m-Y', 'Y-m-d'] as $fmt) {
			$dt = \DateTime::createFromFormat($fmt, $value);
			if ($dt) return $dt->format('Y-m-d');
		}
		// Excel serialized number
		if (is_numeric($value)) {
			return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((float)$value));
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
		$numbers = [];
		preg_match_all('/\d[\d\.,]*/', $raw, $m);
		foreach ($m[0] as $num) {
			$numbers[] = (int) str_replace([',', '.'], '', $num);
		}
		if (count($numbers) >= 2) return [$numbers[0], $numbers[1]];
		if (count($numbers) === 1) return [$numbers[0], null];
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
}


