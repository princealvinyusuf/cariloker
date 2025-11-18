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

class DistributeJobImports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const PROGRESS_KEY = 'job_imports:distribute';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = (int) DB::table('job_imports')->count();

        $state = [
            'total' => $total,
            'processed' => 0,
            'running' => true,
            'errors' => [],
        ];
        Cache::put(self::PROGRESS_KEY, $state, 3600);

        if ($total === 0) {
            $state['running'] = false;
            Cache::put(self::PROGRESS_KEY, $state, 3600);
            return;
        }

        $processed = 0;
        $errors = [];

        DB::table('job_imports')
            ->orderBy('id')
            ->chunkById(50, function ($rows) use (&$processed, $total, &$errors) {
                foreach ($rows as $row) {
                    try {
                        $this->processRow($row);
                    } catch (\Throwable $e) {
                        if (count($errors) < 20) {
                            $errors[] = $e->getMessage();
                        }
                    }

                    $processed++;

                    Cache::put(self::PROGRESS_KEY, [
                        'total' => $total,
                        'processed' => $processed,
                        'running' => true,
                        'errors' => $errors,
                    ], 3600);
                }
            });

        Cache::put(self::PROGRESS_KEY, [
            'total' => $total,
            'processed' => $processed,
            'running' => false,
            'errors' => $errors,
        ], 3600);
    }

    protected function processRow(object $row): void
    {
        $companyName = trim((string) ($row->nama_perusahaan ?? ''));
        if ($companyName === '') {
            return;
        }

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

        $slugBase = Str::slug(substr($title, 0, 200));
        if ($slugBase === '') {
            $slugBase = Str::uuid()->toString();
        }
        $slug = $slugBase;
        $suffix = 1;
        while (Job::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $suffix++;
        }

        $employmentType = $this->mapEmploymentType($row->tipe_pekerjaan ?? null);
        $workArrangement = $this->mapWorkArrangement($row->kondisi ?? null);
        $gender = $this->normalizeNullableString($row->jenis_kelamin ?? null);
        $externalUrl = $this->normalizeNullableString($row->url ?? null);

        $jobData = [
            'company_id' => $company->id,
            'category_id' => $category?->id,
            'location_id' => $location?->id,
            'title' => $title,
            'slug' => $slug,
            'description' => $this->normalizeNullableString($row->deskripsi ?? null),
            'employment_type' => $employmentType,
            'external_url' => $externalUrl,
            'gender' => $gender,
            'work_arrangement' => $workArrangement,
            'education_level' => $this->normalizeNullableString($row->pendidikan ?? null),
            'status' => 'published',
        ];

        Job::create($jobData);
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

        return null;
    }

    protected function normalizeNullableString(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}


