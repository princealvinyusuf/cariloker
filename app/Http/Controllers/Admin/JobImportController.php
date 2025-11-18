<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobImportController extends Controller
{
    /**
     * Distribute data from the staging table (job_imports) into the main tables.
     *
     * This provides a simple, idempotent-ish import that:
     * - Creates/updates companies
     * - Creates locations
     * - Creates categories
     * - Creates job listings
     */
    public function distribute(): RedirectResponse
    {
        $imports = DB::table('job_imports')->get();

        if ($imports->isEmpty()) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'No data found in job_imports staging table.');
        }

        $createdJobs = 0;

        DB::transaction(function () use ($imports, &$createdJobs) {
            foreach ($imports as $row) {
                // Company
                $companyName = trim((string) ($row->nama_perusahaan ?? ''));
                if ($companyName === '') {
                    // Skip rows without company
                    continue;
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

                // Location
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

                // Category
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

                // Basic mapping from staging fields to job listing
                $title = trim((string) ($row->jabatan ?? ''));
                if ($title === '') {
                    // Ensure we always have a title
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
                $createdJobs++;
            }
        });

        return redirect()
            ->route('dashboard')
            ->with('status', "Distribute job_imports completed. Created {$createdJobs} job listings.");
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


