<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class DistributeJobImports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const PROGRESS_KEY = 'job_imports:distribute';
    public const LOCK_KEY = 'job_imports:distribute:lock';
    private const PROGRESS_TTL_SECONDS = 21600;

    /**
     * Increase timeout for large imports and avoid automatic retries
     * that can duplicate work when processing very large datasets.
     */
    public int $timeout = 7200;
    public int $tries = 1;

    public function failed(Throwable $exception): void
    {
        Cache::forget(self::LOCK_KEY);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = (int) DB::table('job_imports')->count();

        $state = [
            'total' => $total,
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'running' => true,
            'errors' => [],
        ];
        Cache::put(self::PROGRESS_KEY, $state, self::PROGRESS_TTL_SECONDS);

        if ($total === 0) {
            $state['running'] = false;
            Cache::put(self::PROGRESS_KEY, $state, self::PROGRESS_TTL_SECONDS);
            Cache::forget(self::LOCK_KEY);
            return;
        }

        $processed = 0;
        $succeeded = 0;
        $failed = 0;
        $skipped = 0;
        $errors = [];

        try {
            DB::table('job_imports')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use (&$processed, &$succeeded, &$failed, &$skipped, $total, &$errors) {
                    foreach ($rows as $row) {
                        try {
                            $result = $this->processRow($row);
                            if ($result === 'skipped') {
                                $skipped++;
                            } else {
                                $succeeded++;
                            }
                        } catch (\Throwable $e) {
                            $failed++;
                            if (count($errors) < 20) {
                                $errors[] = $e->getMessage();
                            }
                        }

                        $processed++;
                    }

                    // Update progress once per chunk to avoid cache write hotspot.
                    Cache::put(self::PROGRESS_KEY, [
                        'total' => $total,
                        'processed' => $processed,
                        'succeeded' => $succeeded,
                        'failed' => $failed,
                        'skipped' => $skipped,
                        'running' => true,
                        'errors' => $errors,
                    ], self::PROGRESS_TTL_SECONDS);
                });
        } finally {
            Cache::put(self::PROGRESS_KEY, [
                'total' => $total,
                'processed' => $processed,
                'succeeded' => $succeeded,
                'failed' => $failed,
                'skipped' => $skipped,
                'running' => false,
                'errors' => $errors,
            ], self::PROGRESS_TTL_SECONDS);
            Cache::forget(self::LOCK_KEY);
        }
    }

    protected function processRow(object $row): string
    {
        $companyName = trim((string) ($row->nama_perusahaan ?? ''));
        if ($companyName === '') {
            return 'skipped';
        }

        return DB::transaction(function () use ($row, $companyName) {
            $companySlug = Str::slug(substr($companyName, 0, 200));
            if ($companySlug === '') {
                $companySlug = Str::uuid()->toString();
            }

            $company = Company::firstOrCreate(
                ['slug' => $companySlug],
                [
                    'name' => $companyName,
                    'logo_path' => $row->logo ?? null,
                    'website_url' => null,
                ]
            );

            $city = trim((string) ($row->kab_kota ?? ''));
            $state = trim((string) ($row->provinsi ?? ''));

            if ($city === '' && $state === '') {
                $location = null;
            } else {
                if ($city === '' && $state !== '') {
                    $city = $state;
                }
                $location = Location::firstOrCreate(
                    [
                        'city' => $city,
                        'state' => $state ?: null,
                        'country' => 'ID',
                    ]
                );
            }

            $categoryName = trim((string) ($row->bidang_pekerjaan ?? ''));
            $category = null;
            if ($categoryName !== '') {
                $categorySlug = Str::slug(substr($categoryName, 0, 200));
                if ($categorySlug === '') {
                    $categorySlug = Str::uuid()->toString();
                }
                $category = JobCategory::firstOrCreate(
                    ['slug' => $categorySlug],
                    ['name' => $categoryName]
                );
            }

            $title = trim((string) ($row->jabatan ?? ''));
            if ($title === '') {
                $title = $company->name;
            }

            $employmentType = $this->mapEmploymentType($row->tipe_pekerjaan ?? null);
            $workArrangement = $this->mapWorkArrangement($row->kondisi ?? null);
            $gender = $this->normalizeNullableString($row->jenis_kelamin ?? null);
            $externalUrl = $this->normalizeNullableString($row->url ?? null);
            $description = $this->normalizeNullableString($row->deskripsi ?? null);
            $educationLevel = $this->normalizeNullableString($row->pendidikan ?? null);
            $sourceHash = $this->buildSourceHash($companyName, $title, $externalUrl, $description, $city ?? null, $state ?? null);

            if (Job::withoutGlobalScope('notExpired')->where('source_hash', $sourceHash)->exists()) {
                return 'skipped';
            }

            $slug = $this->buildUniqueSlug($title);

            $jobData = [
                'company_id' => $company->id,
                'category_id' => $category?->id,
                'location_id' => $location?->id,
                'title' => $title,
                'slug' => $slug,
                'source_hash' => $sourceHash,
                'description' => $description,
                'employment_type' => $employmentType,
                'external_url' => $externalUrl,
                'gender' => $gender,
                'work_arrangement' => $workArrangement,
                'education_level' => $educationLevel,
                'status' => 'published',
            ];

            Job::create($jobData);

            return 'succeeded';
        }, 3);
    }

    protected function mapEmploymentType(?string $type): string
    {
        if (!$type) {
            return 'full_time';
        }

        $normalized = Str::of($type)->lower();

        if ($normalized->contains('part')) {
            return 'part_time';
        }
        if ($normalized->contains('contract')) {
            return 'contract';
        }
        if ($normalized->contains('intern')) {
            return 'internship';
        }
        if ($normalized->contains('freelance') || $normalized->contains('project')) {
            return 'freelance';
        }

        return 'full_time';
    }

    protected function mapWorkArrangement(?string $kondisi): ?string
    {
        if (!$kondisi) {
            return null;
        }

        $normalized = Str::of($kondisi)->lower();

        if ($normalized->contains('remote') || $normalized->contains('wfh')) {
            return 'remote';
        }
        if ($normalized->contains('onsite') || $normalized->contains('on site') || $normalized->contains('on-site')) {
            return 'onsite';
        }
        if ($normalized->contains('hybrid')) {
            return 'hybrid';
        }

        return null;
    }

    protected function buildUniqueSlug(string $title): string
    {
        $slugBase = Str::slug(substr($title, 0, 200));
        if ($slugBase === '') {
            return Str::uuid()->toString();
        }

        $slug = $slugBase;
        $suffix = 1;
        while (Job::withoutGlobalScope('notExpired')->where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $suffix++;
        }

        return $slug;
    }

    protected function buildSourceHash(
        string $companyName,
        string $title,
        ?string $externalUrl,
        ?string $description,
        ?string $city,
        ?string $state
    ): string {
        $parts = [
            Str::lower(trim($companyName)),
            Str::lower(trim($title)),
            Str::lower(trim((string) $externalUrl)),
            Str::lower(trim((string) $description)),
            Str::lower(trim((string) $city)),
            Str::lower(trim((string) $state)),
        ];

        return hash('sha256', implode('|', $parts));
    }

    protected function normalizeNullableString(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}


