<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    private const CHUNK_SIZE = 200;
    private const MAX_ERROR_MESSAGES = 20;

    /** @var array<string, int> */
    protected array $companyIdBySlug = [];

    /** @var array<string, int> */
    protected array $categoryIdBySlug = [];

    /** @var array<string, int> */
    protected array $locationIdByKey = [];

    /** @var array<string, true> */
    protected array $seenSourceHashes = [];

    /** @var array<int, true> */
    protected array $companyIndustryApplied = [];

    public function failed(Throwable $exception): void
    {
        $state = Cache::get(self::PROGRESS_KEY, []);
        if (!is_array($state)) {
            $state = [];
        }

        $errors = $state['errors'] ?? [];
        if (!is_array($errors)) {
            $errors = [];
        }
        if (count($errors) < self::MAX_ERROR_MESSAGES) {
            $errors[] = 'Queue job failed: ' . $exception->getMessage();
        }

        Cache::put(self::PROGRESS_KEY, [
            'total' => (int) ($state['total'] ?? 0),
            'processed' => (int) ($state['processed'] ?? 0),
            'succeeded' => (int) ($state['succeeded'] ?? 0),
            'failed' => max(1, (int) ($state['failed'] ?? 0)),
            'skipped' => (int) ($state['skipped'] ?? 0),
            'running' => false,
            'started_at' => null,
            'elapsed_seconds' => (int) ($state['elapsed_seconds'] ?? 0),
            'eta_seconds' => 0,
            'rows_per_second' => (float) ($state['rows_per_second'] ?? 0),
            'chunk_rows_per_second' => null,
            'queue_warning' => null,
            'errors' => $errors,
        ], self::PROGRESS_TTL_SECONDS);

        Log::error('DistributeJobImports failed', [
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
        ]);

        Cache::forget(self::LOCK_KEY);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = (int) DB::table('job_imports')->count();
        $existing = Cache::get(self::PROGRESS_KEY, []);
        $startedAtTimestamp = is_array($existing) && !empty($existing['started_at'])
            ? (int) $existing['started_at']
            : now()->timestamp;

        $state = [
            'total' => $total,
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'running' => true,
            'started_at' => $startedAtTimestamp,
            'queue_warning' => null,
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
        $startedAt = microtime(true);

        try {
            DB::table('job_imports')
                ->orderBy('id')
                ->chunkById(self::CHUNK_SIZE, function ($rows) use (&$processed, &$succeeded, &$failed, &$skipped, $total, &$errors, $startedAt, $startedAtTimestamp) {
                    $chunkStartedAt = microtime(true);
                    $result = $this->processChunk($rows);
                    $processed += $result['processed'];
                    $succeeded += $result['succeeded'];
                    $failed += $result['failed'];
                    $skipped += $result['skipped'];
                    if (!empty($result['errors'])) {
                        $remaining = max(0, self::MAX_ERROR_MESSAGES - count($errors));
                        if ($remaining > 0) {
                            $errors = array_merge($errors, array_slice($result['errors'], 0, $remaining));
                        }
                    }

                    $elapsedSeconds = max(0.001, microtime(true) - $startedAt);
                    $chunkElapsedSeconds = max(0.001, microtime(true) - $chunkStartedAt);
                    $rowsPerSecond = round($processed / $elapsedSeconds, 2);
                    $chunkRowsPerSecond = round($result['processed'] / $chunkElapsedSeconds, 2);
                    $remainingRows = max(0, $total - $processed);
                    $etaSeconds = $rowsPerSecond > 0 ? (int) ceil($remainingRows / $rowsPerSecond) : null;

                    // Update progress once per chunk to avoid cache write hotspot.
                    Cache::put(self::PROGRESS_KEY, [
                        'total' => $total,
                        'processed' => $processed,
                        'succeeded' => $succeeded,
                        'failed' => $failed,
                        'skipped' => $skipped,
                        'running' => true,
                        'started_at' => $startedAtTimestamp,
                        'elapsed_seconds' => (int) floor($elapsedSeconds),
                        'eta_seconds' => $etaSeconds,
                        'rows_per_second' => $rowsPerSecond,
                        'chunk_rows_per_second' => $chunkRowsPerSecond,
                        'queue_warning' => null,
                        'errors' => $errors,
                    ], self::PROGRESS_TTL_SECONDS);

                    Log::info('job_imports chunk processed', [
                        'chunk_size' => $result['processed'],
                        'processed' => $processed,
                        'total' => $total,
                        'succeeded' => $succeeded,
                        'failed' => $failed,
                        'skipped' => $skipped,
                        'rows_per_second' => $rowsPerSecond,
                        'chunk_rows_per_second' => $chunkRowsPerSecond,
                        'elapsed_seconds' => (int) floor($elapsedSeconds),
                        'eta_seconds' => $etaSeconds,
                    ]);
                });
        } catch (Throwable $e) {
            $failed = max(1, $failed);
            if (count($errors) < self::MAX_ERROR_MESSAGES) {
                $errors[] = 'Fatal import error: ' . $e->getMessage();
            }
            Log::error('DistributeJobImports fatal error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
            throw $e;
        } finally {
            $elapsedSeconds = max(0.001, microtime(true) - $startedAt);
            $rowsPerSecond = round($processed / $elapsedSeconds, 2);
            Cache::put(self::PROGRESS_KEY, [
                'total' => $total,
                'processed' => $processed,
                'succeeded' => $succeeded,
                'failed' => $failed,
                'skipped' => $skipped,
                'running' => false,
                'started_at' => null,
                'elapsed_seconds' => (int) floor($elapsedSeconds),
                'eta_seconds' => 0,
                'rows_per_second' => $rowsPerSecond,
                'chunk_rows_per_second' => null,
                'queue_warning' => null,
                'errors' => $errors,
            ], self::PROGRESS_TTL_SECONDS);
            Cache::forget(self::LOCK_KEY);
        }
    }

    /**
     * @param iterable<int, object> $rows
     * @return array{processed:int,succeeded:int,failed:int,skipped:int,errors:array<int,string>}
     */
    protected function processChunk(iterable $rows): array
    {
        $stats = [
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $preparedRows = [];
        foreach ($rows as $row) {
            $preparedRows[] = $this->prepareRow($row);
        }

        $this->primeCompanies($preparedRows);
        $this->primeCategories($preparedRows);
        $this->primeLocations($preparedRows);
        $existingHashSet = $this->loadExistingSourceHashSet($preparedRows);

        foreach ($preparedRows as $row) {
            $stats['processed']++;

            if ($row['is_skippable']) {
                $stats['skipped']++;
                continue;
            }

            $sourceHash = $row['source_hash'];
            if (isset($this->seenSourceHashes[$sourceHash]) || isset($existingHashSet[$sourceHash])) {
                $stats['skipped']++;
                continue;
            }

            try {
                $result = $this->createJobFromPrepared($row);
                if ($result === 'succeeded') {
                    $stats['succeeded']++;
                    $this->seenSourceHashes[$sourceHash] = true;
                } else {
                    $stats['skipped']++;
                }
            } catch (Throwable $e) {
                $stats['failed']++;
                if (count($stats['errors']) < self::MAX_ERROR_MESSAGES) {
                    $stats['errors'][] = $e->getMessage();
                }
            }
        }

        return $stats;
    }

    /**
     * @param array<int, array<string, mixed>> $preparedRows
     */
    protected function primeCompanies(array $preparedRows): void
    {
        $candidates = [];
        foreach ($preparedRows as $row) {
            if ($row['is_skippable']) {
                continue;
            }
            $slug = $row['company_slug'];
            if (isset($this->companyIdBySlug[$slug])) {
                continue;
            }
            if (!isset($candidates[$slug])) {
                $candidates[$slug] = [
                    'slug' => $slug,
                    'name' => $row['company_name'],
                    'logo_path' => $row['logo_path'],
                    'website_url' => null,
                    'industry' => $row['sector_text'],
                ];
            }
        }

        if ($candidates === []) {
            return;
        }

        $slugs = array_keys($candidates);
        $existing = Company::query()->whereIn('slug', $slugs)->pluck('id', 'slug')->all();
        foreach ($existing as $slug => $id) {
            $this->companyIdBySlug[$slug] = (int) $id;
            unset($candidates[$slug]);
        }

        if ($candidates !== []) {
            $now = now();
            $insertRows = [];
            foreach ($candidates as $row) {
                $row['created_at'] = $now;
                $row['updated_at'] = $now;
                $insertRows[] = $row;
            }
            DB::table('companies')->insertOrIgnore($insertRows);

            $newlyExisting = Company::query()->whereIn('slug', array_keys($candidates))->pluck('id', 'slug')->all();
            foreach ($newlyExisting as $slug => $id) {
                $this->companyIdBySlug[$slug] = (int) $id;
            }
        }
    }

    /**
     * @param array<int, array<string, mixed>> $preparedRows
     */
    protected function primeCategories(array $preparedRows): void
    {
        $candidates = [];
        foreach ($preparedRows as $row) {
            if ($row['is_skippable'] || $row['category_slug'] === null) {
                continue;
            }
            $slug = $row['category_slug'];
            if (isset($this->categoryIdBySlug[$slug])) {
                continue;
            }
            if (!isset($candidates[$slug])) {
                $candidates[$slug] = [
                    'slug' => $slug,
                    'name' => $row['category_name'],
                ];
            }
        }

        if ($candidates === []) {
            return;
        }

        $slugs = array_keys($candidates);
        $existing = JobCategory::query()->whereIn('slug', $slugs)->pluck('id', 'slug')->all();
        foreach ($existing as $slug => $id) {
            $this->categoryIdBySlug[$slug] = (int) $id;
            unset($candidates[$slug]);
        }

        if ($candidates !== []) {
            $now = now();
            $insertRows = [];
            foreach ($candidates as $row) {
                $row['created_at'] = $now;
                $row['updated_at'] = $now;
                $insertRows[] = $row;
            }
            DB::table('job_categories')->insertOrIgnore($insertRows);

            $newlyExisting = JobCategory::query()->whereIn('slug', array_keys($candidates))->pluck('id', 'slug')->all();
            foreach ($newlyExisting as $slug => $id) {
                $this->categoryIdBySlug[$slug] = (int) $id;
            }
        }
    }

    /**
     * @param array<int, array<string, mixed>> $preparedRows
     */
    protected function primeLocations(array $preparedRows): void
    {
        $candidates = [];
        foreach ($preparedRows as $row) {
            if ($row['is_skippable'] || $row['location'] === null) {
                continue;
            }
            $location = $row['location'];
            $key = $this->locationKey($location['city'], $location['state'], $location['country']);
            if (isset($this->locationIdByKey[$key])) {
                continue;
            }
            $candidates[$key] = $location;
        }

        if ($candidates === []) {
            return;
        }

        $tuples = array_values($candidates);
        foreach (array_chunk($tuples, 100) as $tupleChunk) {
            $existingChunk = Location::query()
                ->where(function ($query) use ($tupleChunk) {
                    foreach ($tupleChunk as $tuple) {
                        $query->orWhere(function ($sub) use ($tuple) {
                            $sub->where('city', $tuple['city'])
                                ->where('country', $tuple['country']);
                            if ($tuple['state'] === null) {
                                $sub->whereNull('state');
                            } else {
                                $sub->where('state', $tuple['state']);
                            }
                        });
                    }
                })
                ->get(['id', 'city', 'state', 'country']);

            foreach ($existingChunk as $location) {
                $key = $this->locationKey($location->city, $location->state, $location->country);
                $this->locationIdByKey[$key] = (int) $location->id;
            }
        }

        foreach ($candidates as $key => $location) {
            if (isset($this->locationIdByKey[$key])) {
                continue;
            }
        }

        $toInsert = [];
        foreach ($candidates as $key => $location) {
            if (!isset($this->locationIdByKey[$key])) {
                $toInsert[] = $location;
            }
        }

        if ($toInsert !== []) {
            $now = now();
            $insertRows = [];
            foreach ($toInsert as $location) {
                $insertRows[] = [
                    'city' => $location['city'],
                    'state' => $location['state'],
                    'country' => $location['country'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('locations')->insertOrIgnore($insertRows);
        }

        // Finalize unresolved keys via batched reload.
        $unresolved = [];
        foreach ($candidates as $key => $location) {
            if (!isset($this->locationIdByKey[$key])) {
                $unresolved[] = $location;
            }
        }

        foreach (array_chunk($unresolved, 100) as $tupleChunk) {
            $resolvedChunk = Location::query()
                ->where(function ($query) use ($tupleChunk) {
                    foreach ($tupleChunk as $tuple) {
                        $query->orWhere(function ($sub) use ($tuple) {
                            $sub->where('city', $tuple['city'])
                                ->where('country', $tuple['country']);
                            if ($tuple['state'] === null) {
                                $sub->whereNull('state');
                            } else {
                                $sub->where('state', $tuple['state']);
                            }
                        });
                    }
                })
                ->get(['id', 'city', 'state', 'country']);

            foreach ($resolvedChunk as $location) {
                $key = $this->locationKey($location->city, $location->state, $location->country);
                $this->locationIdByKey[$key] = (int) $location->id;
            }
        }
    }

    /**
     * @param array<int, array<string, mixed>> $preparedRows
     * @return array<string, true>
     */
    protected function loadExistingSourceHashSet(array $preparedRows): array
    {
        $hashes = [];
        foreach ($preparedRows as $row) {
            if ($row['is_skippable']) {
                continue;
            }
            $hash = $row['source_hash'];
            if (!isset($this->seenSourceHashes[$hash])) {
                $hashes[$hash] = true;
            }
        }

        if ($hashes === []) {
            return [];
        }

        $existingHashes = Job::withoutGlobalScope('notExpired')
            ->whereIn('source_hash', array_keys($hashes))
            ->pluck('source_hash')
            ->all();

        return array_fill_keys($existingHashes, true);
    }

    /**
     * @param array<string, mixed> $row
     */
    protected function createJobFromPrepared(array $row): string
    {
        $companyId = $this->companyIdBySlug[$row['company_slug']] ?? null;
        if ($companyId === null) {
            throw new \RuntimeException('Company mapping missing for slug: ' . $row['company_slug']);
        }

        $categoryId = null;
        if ($row['category_slug'] !== null) {
            $categoryId = $this->categoryIdBySlug[$row['category_slug']] ?? null;
        }

        $locationId = null;
        if ($row['location'] !== null) {
            $location = $row['location'];
            $locationKey = $this->locationKey($location['city'], $location['state'], $location['country']);
            $locationId = $this->locationIdByKey[$locationKey] ?? null;
        }

        $this->ensureCompanyIndustry($companyId, $row['sector_text']);

        $baseSlug = $this->buildSlugBase($row['title']);
        $hashSuffix = substr((string) $row['source_hash'], 0, 20);
        $description = $row['description'] ?? '';
        $requirements = $row['requirements'];
        $salaryRange = $this->parseSalaryRange($row['salary_text']);
        $experienceRange = $this->parseExperienceRange($row['experience_text']);
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $slug = $this->buildSlugCandidate($baseSlug, $hashSuffix, $attempt);
            $jobData = [
                'company_id' => $companyId,
                'category_id' => $categoryId,
                'location_id' => $locationId,
                'title' => $row['title'],
                'slug' => $slug,
                'source_hash' => $row['source_hash'],
                'description' => $description,
                'requirements' => $requirements,
                'sector_text' => $row['sector_text'],
                'openings' => $row['openings'],
                'posted_at' => $row['posted_at'],
                'valid_until' => $row['valid_until'],
                'employment_type' => $row['employment_type'],
                'external_url' => $row['external_url'],
                'gender' => $row['gender'],
                'physical_condition' => $row['physical_condition'],
                'work_arrangement' => $row['work_arrangement'],
                'seniority_level' => $row['seniority_level'],
                'education_level' => $row['education_level'],
                'salary_text' => $row['salary_text'],
                'experience_text' => $row['experience_text'],
                'salary_min' => $salaryRange['min'],
                'salary_max' => $salaryRange['max'],
                'salary_currency' => $salaryRange['currency'] ?? 'IDR',
                'experience_min' => $experienceRange['min'],
                'experience_max' => $experienceRange['max'],
                'status' => 'published',
            ];

            try {
                Job::create($jobData);
                return 'succeeded';
            } catch (QueryException $e) {
                if (!$this->isDuplicateConstraintViolation($e)) {
                    throw $e;
                }
                if (Job::withoutGlobalScope('notExpired')->where('source_hash', $row['source_hash'])->exists()) {
                    return 'skipped';
                }
            }
        }

        // Extremely rare fallback to guarantee forward progress even under heavy collisions.
        $fallbackData = [
            'company_id' => $companyId,
            'category_id' => $categoryId,
            'location_id' => $locationId,
            'title' => $row['title'],
            'slug' => substr($baseSlug . '-' . Str::lower(Str::uuid()->toString()), 0, 255),
            'source_hash' => $row['source_hash'],
            'description' => $description,
            'requirements' => $requirements,
            'sector_text' => $row['sector_text'],
            'openings' => $row['openings'],
            'posted_at' => $row['posted_at'],
            'valid_until' => $row['valid_until'],
            'employment_type' => $row['employment_type'],
            'external_url' => $row['external_url'],
            'gender' => $row['gender'],
            'physical_condition' => $row['physical_condition'],
            'work_arrangement' => $row['work_arrangement'],
            'seniority_level' => $row['seniority_level'],
            'education_level' => $row['education_level'],
            'salary_text' => $row['salary_text'],
            'experience_text' => $row['experience_text'],
            'salary_min' => $salaryRange['min'],
            'salary_max' => $salaryRange['max'],
            'salary_currency' => $salaryRange['currency'] ?? 'IDR',
            'experience_min' => $experienceRange['min'],
            'experience_max' => $experienceRange['max'],
            'status' => 'published',
        ];

        try {
            Job::create($fallbackData);
            return 'succeeded';
        } catch (QueryException $e) {
            if ($this->isDuplicateConstraintViolation($e)
                && Job::withoutGlobalScope('notExpired')->where('source_hash', $row['source_hash'])->exists()) {
                return 'skipped';
            }
            throw $e;
        }
    }

    protected function isDuplicateConstraintViolation(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');
        return $sqlState === '23000' || $sqlState === '23505';
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareRow(object $row): array
    {
        $companyName = trim((string) ($row->nama_perusahaan ?? ''));
        if ($companyName === '') {
            return ['is_skippable' => true];
        }

        $companySlug = Str::slug(substr($companyName, 0, 200));
        if ($companySlug === '') {
            $companySlug = Str::uuid()->toString();
        }

        $city = trim((string) ($row->kab_kota ?? ''));
        $state = trim((string) ($row->provinsi ?? ''));
        if ($city === '' && $state !== '') {
            $city = $state;
        }
        $location = null;
        if ($city !== '' || $state !== '') {
            $location = [
                'city' => $city,
                'state' => $state !== '' ? $state : null,
                'country' => 'ID',
            ];
        }

        $categoryName = trim((string) ($row->bidang_pekerjaan ?? ''));
        $categorySlug = null;
        if ($categoryName !== '') {
            $categorySlug = Str::slug(substr($categoryName, 0, 200));
            if ($categorySlug === '') {
                $categorySlug = Str::uuid()->toString();
            }
        }

        $title = trim((string) ($row->jabatan ?? ''));
        if ($title === '') {
            $title = $companyName;
        }

        $externalUrl = $this->normalizeNullableString($row->url ?? null);
        $description = $this->normalizeNullableString($row->deskripsi ?? null);
        $requirements = $this->normalizeNullableString($row->keahlian ?? null);
        $openings = $this->parseNullableUnsignedInt($row->jumlah_lowongan ?? null);
        $postedAt = $this->parseImportDate($row->tanggal_posting ?? null, false);
        $validUntil = $this->parseImportDate($row->tanggal_berakhir ?? null, true);
        $physicalCondition = $this->normalizeNullableString($row->kondisi ?? null);
        $seniorityLevel = $this->normalizeNullableString($row->tingkat_pekerjaan ?? null);
        $salaryText = $this->normalizeNullableString($row->gaji ?? null);
        $experienceText = $this->normalizeNullableString($row->pengalaman ?? null);
        $sectorText = $this->normalizeNullableString($row->sektor ?? null);

        return [
            'is_skippable' => false,
            'company_name' => $companyName,
            'company_slug' => $companySlug,
            'logo_path' => $row->logo ?? null,
            'location' => $location,
            'category_name' => $categoryName !== '' ? $categoryName : null,
            'category_slug' => $categorySlug,
            'title' => $title,
            'employment_type' => $this->mapEmploymentType($row->tipe_pekerjaan ?? null),
            'work_arrangement' => $this->mapWorkArrangement($row->kondisi ?? null),
            'gender' => $this->normalizeNullableString($row->jenis_kelamin ?? null),
            'physical_condition' => $physicalCondition,
            'external_url' => $externalUrl,
            'description' => $description,
            'requirements' => $requirements,
            'sector_text' => $sectorText,
            'openings' => $openings,
            'posted_at' => $postedAt,
            'valid_until' => $validUntil,
            'seniority_level' => $seniorityLevel,
            'education_level' => $this->normalizeNullableString($row->pendidikan ?? null),
            'salary_text' => $salaryText,
            'experience_text' => $experienceText,
            'source_hash' => $this->buildSourceHash(
                $companyName,
                $title,
                $externalUrl,
                $description,
                $requirements,
                $sectorText,
                $physicalCondition,
                $seniorityLevel,
                $salaryText,
                $experienceText,
                $location['city'] ?? null,
                $location['state'] ?? null
            ),
        ];
    }

    protected function mapEmploymentType(?string $type): string
    {
        if (!$type) {
            return 'full_time';
        }

        $normalized = Str::of($type)->lower();

        if ($normalized->contains('paruh') || $normalized->contains('part')) {
            return 'part_time';
        }
        if ($normalized->contains('kontrak') || $normalized->contains('contract')) {
            return 'contract';
        }
        if ($normalized->contains('magang') || $normalized->contains('intern')) {
            return 'internship';
        }
        if ($normalized->contains('freelance') || $normalized->contains('project') || $normalized->contains('lepas')) {
            return 'freelance';
        }
        if ($normalized->contains('tetap') || $normalized->contains('full') || $normalized->contains('penuh')) {
            return 'full_time';
        }

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

    protected function buildSlugBase(string $title): string
    {
        $slugBase = Str::slug(substr($title, 0, 160));
        if ($slugBase === '') {
            $slugBase = 'job';
        }

        return $slugBase;
    }

    protected function buildSlugCandidate(string $baseSlug, string $hashSuffix, int $attempt): string
    {
        $candidate = $baseSlug . '-' . $hashSuffix;
        if ($attempt > 0) {
            $candidate .= '-' . base_convert((string) $attempt, 10, 36);
        }

        return substr($candidate, 0, 255);
    }

    protected function buildSourceHash(
        string $companyName,
        string $title,
        ?string $externalUrl,
        ?string $description,
        ?string $requirements,
        ?string $sectorText,
        ?string $physicalCondition,
        ?string $seniorityLevel,
        ?string $salaryText,
        ?string $experienceText,
        ?string $city,
        ?string $state
    ): string {
        $parts = [
            Str::lower(trim($companyName)),
            Str::lower(trim($title)),
            Str::lower(trim((string) $externalUrl)),
            Str::lower(trim((string) $description)),
            Str::lower(trim((string) $requirements)),
            Str::lower(trim((string) $sectorText)),
            Str::lower(trim((string) $physicalCondition)),
            Str::lower(trim((string) $seniorityLevel)),
            Str::lower(trim((string) $salaryText)),
            Str::lower(trim((string) $experienceText)),
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

    protected function parseImportDate(?string $value, bool $dateOnly): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/y', 'd-m-y'];
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date !== false) {
                    return $dateOnly
                        ? $date->format('Y-m-d')
                        : $date->startOfDay()->format('Y-m-d H:i:s');
                }
            } catch (\Throwable $e) {
                // Try next format.
            }
        }

        try {
            $date = Carbon::parse($value);
            return $dateOnly
                ? $date->format('Y-m-d')
                : $date->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param mixed $value
     */
    protected function parseNullableUnsignedInt($value): ?int
    {
        if ($value === null) {
            return null;
        }

        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === null || $digits === '') {
            return null;
        }

        $number = (int) $digits;
        return $number >= 0 ? $number : null;
    }

    /**
     * @return array{min:?int,max:?int,currency:?string}
     */
    protected function parseSalaryRange(?string $salaryText): array
    {
        $salaryText = trim((string) $salaryText);
        if ($salaryText === '') {
            return ['min' => null, 'max' => null, 'currency' => 'IDR'];
        }

        $currency = Str::contains(Str::upper($salaryText), 'USD') ? 'USD' : 'IDR';
        preg_match_all('/\d[\d\.,]*/', $salaryText, $matches);
        $numbers = collect($matches[0] ?? [])
            ->map(function (string $num) {
                $digits = preg_replace('/\D+/', '', $num);
                return $digits === '' ? null : (int) $digits;
            })
            ->filter(fn ($n) => $n !== null && $n > 0)
            ->values()
            ->all();

        if ($numbers === []) {
            return ['min' => null, 'max' => null, 'currency' => $currency];
        }

        $min = (int) min($numbers);
        $max = (int) max($numbers);
        if ($max < $min) {
            $max = $min;
        }

        return ['min' => $min, 'max' => $max, 'currency' => $currency];
    }

    /**
     * @return array{min:?int,max:?int}
     */
    protected function parseExperienceRange(?string $experienceText): array
    {
        $experienceText = trim((string) $experienceText);
        if ($experienceText === '') {
            return ['min' => null, 'max' => null];
        }

        preg_match_all('/\d+/', $experienceText, $matches);
        $numbers = collect($matches[0] ?? [])
            ->map(fn (string $n) => (int) $n)
            ->filter(fn (int $n) => $n >= 0)
            ->values()
            ->all();

        if ($numbers === []) {
            return ['min' => null, 'max' => null];
        }

        $min = (int) min($numbers);
        $max = (int) max($numbers);
        if ($max < $min) {
            $max = $min;
        }

        return ['min' => $min, 'max' => $max];
    }

    protected function ensureCompanyIndustry(int $companyId, ?string $sectorText): void
    {
        if ($companyId <= 0 || $sectorText === null || $sectorText === '') {
            return;
        }
        if (isset($this->companyIndustryApplied[$companyId])) {
            return;
        }

        DB::table('companies')
            ->where('id', $companyId)
            ->where(function ($query) {
                $query->whereNull('industry')->orWhere('industry', '');
            })
            ->update(['industry' => $sectorText, 'updated_at' => now()]);

        $this->companyIndustryApplied[$companyId] = true;
    }

    protected function locationKey(string $city, ?string $state, string $country): string
    {
        return Str::lower($city) . '|' . Str::lower((string) $state) . '|' . Str::lower($country);
    }
}


