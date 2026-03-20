<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:job-listing')->only('index');
        $this->middleware('throttle:job-detail')->only('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->renderListingPage($request);
    }

    /**
     * SEO landing page for a specific category.
     */
    public function byCategory(Request $request, JobCategory $category)
    {
        $request->merge([
            'category' => $category->slug,
        ]);

        return $this->renderListingPage(
            $request,
            [
                'metaTitle' => sprintf('Lowongan Kerja %s Terbaru - Cari Loker', $category->name),
                'metaDescription' => sprintf('Temukan lowongan kerja %s terbaru dari berbagai perusahaan terbaik di Indonesia.', $category->name),
                'pageHeading' => sprintf('Lowongan Kerja %s', $category->name),
                'pageSubheading' => sprintf('Jelajahi peluang karier %s yang relevan dengan keahlianmu.', $category->name),
                'seoContentTitle' => sprintf('Panduan Mencari Lowongan Kerja %s', $category->name),
                'seoContentBody' => sprintf(
                    'Kategori %s di Cari Loker menampilkan peluang terbaru dari perusahaan aktif di Indonesia. Gunakan filter gaji, pengalaman, dan tipe kerja untuk menemukan posisi yang paling relevan dengan profilmu.',
                    $category->name
                ),
                'seoFaqs' => [
                    [
                        'question' => sprintf('Bagaimana cara menemukan lowongan %s yang sesuai?', $category->name),
                        'answer' => 'Gunakan filter lokasi, pengalaman, dan salary range untuk mempersempit hasil, lalu simpan lowongan yang paling cocok.',
                    ],
                    [
                        'question' => sprintf('Seberapa sering lowongan %s diperbarui?', $category->name),
                        'answer' => 'Lowongan diupdate berkala setiap hari sesuai data terbaru dari perusahaan yang aktif merekrut.',
                    ],
                ],
                'breadcrumbItems' => [
                    ['name' => 'Beranda', 'url' => route('beranda')],
                    ['name' => 'Jobs', 'url' => route('jobs.index')],
                    ['name' => $category->name, 'url' => route('jobs.by-category', $category)],
                ],
            ]
        );
    }

    /**
     * SEO landing page for a specific city/location.
     */
    public function byLocation(Request $request, string $locationSlug)
    {
        $matchedCity = $this->resolveCityFromSlug($locationSlug)
            ?? str($locationSlug)->replace('-', ' ')->title()->toString();

        $request->merge([
            'location' => $matchedCity,
        ]);

        return $this->renderListingPage(
            $request,
            [
                'metaTitle' => sprintf('Lowongan Kerja di %s Terbaru - Cari Loker', $matchedCity),
                'metaDescription' => sprintf('Cari lowongan kerja terbaru di %s. Temukan pekerjaan terbaik sesuai bidang dan pengalamanmu.', $matchedCity),
                'pageHeading' => sprintf('Lowongan Kerja di %s', $matchedCity),
                'pageSubheading' => sprintf('Peluang karier terbaru dari perusahaan terbaik di %s.', $matchedCity),
                'seoContentTitle' => sprintf('Tips Melamar Kerja di %s', $matchedCity),
                'seoContentBody' => sprintf(
                    'Halaman ini menampilkan lowongan aktif di %s dari berbagai industri. Perbarui CV, sesuaikan kata kunci pada profilmu, dan kirim lamaran ke posisi yang paling relevan.',
                    $matchedCity
                ),
                'seoFaqs' => [
                    [
                        'question' => sprintf('Apakah lowongan di %s selalu terbaru?', $matchedCity),
                        'answer' => 'Ya, kami menampilkan lowongan aktif dan memperbaruinya secara berkala agar kandidat melihat peluang terbaru.',
                    ],
                    [
                        'question' => sprintf('Bisakah saya menemukan pekerjaan remote dari %s?', $matchedCity),
                        'answer' => 'Bisa. Gunakan filter work arrangement untuk memilih remote, hybrid, atau onsite sesuai kebutuhan.',
                    ],
                ],
                'breadcrumbItems' => [
                    ['name' => 'Beranda', 'url' => route('beranda')],
                    ['name' => 'Jobs', 'url' => route('jobs.index')],
                    ['name' => $matchedCity, 'url' => route('jobs.by-location', ['locationSlug' => $locationSlug])],
                ],
            ]
        );
    }

    /**
     * SEO landing page for category + city combination.
     */
    public function byCategoryAndLocation(Request $request, JobCategory $category, string $locationSlug)
    {
        $matchedCity = $this->resolveCityFromSlug($locationSlug)
            ?? str($locationSlug)->replace('-', ' ')->title()->toString();

        $request->merge([
            'category' => $category->slug,
            'location' => $matchedCity,
        ]);

        return $this->renderListingPage(
            $request,
            [
                'metaTitle' => sprintf('Lowongan Kerja %s di %s - Cari Loker', $category->name, $matchedCity),
                'metaDescription' => sprintf('Temukan lowongan kerja %s terbaru di %s dari perusahaan terbaik dan posisi paling relevan.', $category->name, $matchedCity),
                'pageHeading' => sprintf('Lowongan %s di %s', $category->name, $matchedCity),
                'pageSubheading' => sprintf('Kumpulan peluang kerja %s terbaru khusus area %s.', $category->name, $matchedCity),
                'seoContentTitle' => sprintf('Peluang Karier %s di %s', $category->name, $matchedCity),
                'seoContentBody' => sprintf(
                    'Halaman ini menampilkan lowongan %s aktif di %s. Gunakan filter tambahan untuk menyeleksi posisi berdasarkan pengalaman, tipe kerja, dan rentang gaji.',
                    $category->name,
                    $matchedCity
                ),
                'seoFaqs' => [
                    [
                        'question' => sprintf('Bagaimana cara melamar lowongan %s di %s?', $category->name, $matchedCity),
                        'answer' => 'Pilih posisi yang sesuai, cek persyaratan, lalu lanjutkan proses aplikasi pada detail lowongan.',
                    ],
                    [
                        'question' => sprintf('Apakah tersedia lowongan remote untuk %s di %s?', $category->name, $matchedCity),
                        'answer' => 'Gunakan filter work arrangement untuk memilih opsi remote, hybrid, atau onsite sesuai preferensi.',
                    ],
                ],
                'breadcrumbItems' => [
                    ['name' => 'Beranda', 'url' => route('beranda')],
                    ['name' => 'Jobs', 'url' => route('jobs.index')],
                    ['name' => $category->name, 'url' => route('jobs.by-category', $category)],
                    ['name' => $matchedCity, 'url' => route('jobs.by-category-location', ['category' => $category, 'locationSlug' => $locationSlug])],
                ],
            ]
        );
    }

    /**
     * Shared listing renderer for jobs index and SEO landing pages.
     */
    private function renderListingPage(Request $request, array $seoOverrides = [])
    {
        $queryCategory = trim((string) $request->input('category'));
        $queryLocation = trim((string) $request->input('location'));
        $queryKeyword = trim((string) $request->input('q'));

        $resolvedCategoryName = null;
        if ($queryCategory !== '') {
            $resolvedCategoryName = JobCategory::query()
                ->where('slug', $queryCategory)
                ->value('name');
        }

        $query = Job::withoutGlobalScope('notExpired')
            ->with(['company', 'location', 'category'])
            ->where('status', 'published')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str($request->string('q'))->trim()->toString() . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('company', fn ($c) => $c->where('name', 'like', $term));
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', function ($c) use ($request) {
                $c->where('slug', $request->string('category'));
            }))
            ->when($request->filled('location'), function ($q) use ($request) {
                $term = '%' . str($request->string('location'))->trim()->toString() . '%';
                $q->whereHas('location', function ($l) use ($term) {
                    $l->where('city', 'like', $term)
                      ->orWhere('state', 'like', $term)
                      ->orWhere('country', 'like', $term);
                });
            })
            ->when($request->filled('type'), fn ($q) => $q->where('employment_type', $request->string('type')))
            ->when($request->boolean('remote') || (is_array($request->work_arrangement) && in_array('remote_check', $request->work_arrangement)), fn ($q) => $q->where('is_remote', true))
            ->when($request->filled('min_salary'), fn ($q) => $q->where('salary_min', '>=', (int) $request->integer('min_salary')))
            ->when($request->filled('salary_range'), function ($q) use ($request) {
                $salaryRanges = is_array($request->salary_range) ? $request->salary_range : [$request->salary_range];
                $q->where(function ($sub) use ($salaryRanges) {
                    foreach ($salaryRanges as $range) {
                        $range = (string) $range;
                        if (str_ends_with($range, '+')) {
                            $min = (int) rtrim($range, '+');
                            $sub->orWhere('salary_min', '>=', $min);
                            continue;
                        }
                        if (str_contains($range, '-')) {
                            [$minStr, $maxStr] = explode('-', $range, 2);
                            $min = (int) $minStr;
                            $max = (int) $maxStr;
                            $sub->orWhere(function ($s) use ($min, $max) {
                                $s->where('salary_min', '>=', $min)->where('salary_min', '<=', $max);
                            });
                        }
                    }
                });
            })
            ->when($request->filled('education_level'), function ($q) use ($request) {
                $selected = $request->input('education_level');
                $levels = is_array($selected) ? $selected : [$selected];
                $levels = array_values(array_filter(array_map(fn ($level) => trim((string) $level), $levels)));
                if (!empty($levels)) {
                    $q->whereIn($q->qualifyColumn('education_level'), $levels);
                }
            })
            ->when($request->filled('experience'), function ($q) use ($request) {
                $experiences = is_array($request->experience) ? $request->experience : [$request->experience];
                $q->where(function ($sub) use ($experiences) {
                    foreach ($experiences as $exp) {
                        $years = (int) $exp;
                        $sub->orWhere(function ($s) use ($years) {
                            $s->whereNull('experience_min')->orWhere('experience_min', '<=', $years);
                        });
                    }
                });
            })
            ->when($request->filled('date_posted'), function ($q) use ($request) {
                $dateRanges = is_array($request->date_posted) ? $request->date_posted : [$request->date_posted];
                $q->where(function ($sub) use ($dateRanges) {
                    foreach ($dateRanges as $range) {
                        switch ($range) {
                            case 'today':
                                $sub->orWhereDate('created_at', now()->toDateString());
                                break;
                            case 'last_7_days':
                                $sub->orWhere('created_at', '>=', now()->subDays(7));
                                break;
                            case 'last_15_days':
                                $sub->orWhere('created_at', '>=', now()->subDays(15));
                                break;
                            case 'last_month':
                                $sub->orWhere('created_at', '>=', now()->subMonth());
                                break;
                        }
                    }
                });
            })
            ->when($request->filled('work_arrangement'), function ($q) use ($request) {
                $arrangements = is_array($request->work_arrangement) ? $request->work_arrangement : [$request->work_arrangement];
                $q->where(function ($sub) use ($arrangements) {
                    foreach ($arrangements as $arrangement) {
                        if ($arrangement === 'onsite') {
                            $sub->orWhere(function ($s) {
                                $s->where('work_arrangement', 'onsite')->orWhere(function ($s2) {
                                    $s2->whereNull('work_arrangement')->where('is_remote', false);
                                });
                            });
                        } elseif ($arrangement === 'remote') {
                            $sub->orWhere(function ($s) {
                                $s->where('work_arrangement', 'remote')->orWhere('is_remote', true);
                            });
                        }
                    }
                });
            });

        $sort = $request->string('sort', 'date');
        $query->orderByRaw("
            CASE
                WHEN valid_until IS NOT NULL AND DATE(valid_until) < ? THEN 1
                ELSE 0
            END ASC
        ", [now()->toDateString()]);

        if ($sort === 'salary') {
            $query->orderByDesc('salary_max');
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(10)->withQueryString();

        // Cache categories
        $categories = Cache::remember('categories:with-count', 600, function() {
            return JobCategory::query()
                ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
                ->orderBy('name')
                ->get();
        });
        // Cache education levels
        $educationLevels = Cache::remember('education_levels:distinct', 600, function() {
            return Job::withoutGlobalScope('notExpired')
                ->whereNotNull('education_level')
                ->where('status', 'published')
                ->select('education_level')
                ->distinct()->orderBy('education_level')->pluck('education_level')->filter()->values();
        });
        // Cache popular companies
        $popularCompanies = Cache::remember('companies:popular', 600, function() {
            return Company::query()
                ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
                ->orderByDesc('jobs_count')->limit(8)->get();
        });

        $popularLocations = Cache::remember('locations:popular', 600, function () {
            return Location::query()
                ->whereNotNull('city')
                ->whereHas('jobs', fn ($q) => $q->where('status', 'published'))
                ->withCount(['jobs' => fn ($q) => $q->where('status', 'published')])
                ->orderByDesc('jobs_count')
                ->orderBy('city')
                ->limit(10)
                ->get();
        });

        $searchCombos = collect();
        $comboCategories = $categories->take(4);
        $comboLocations = $popularLocations->take(4);
        foreach ($comboCategories as $categoryItem) {
            foreach ($comboLocations as $locationItem) {
                $searchCombos->push([
                    'label' => sprintf('Lowongan %s di %s', $categoryItem->name, $locationItem->city),
                    'url' => route('jobs.by-category-location', [
                        'category' => $categoryItem,
                        'locationSlug' => str((string) $locationItem->city)->slug(),
                    ]),
                ]);
                if ($searchCombos->count() >= 12) {
                    break 2;
                }
            }
        }

        $relatedCategoryLinks = collect();
        $relatedLocationLinks = collect();

        if ($resolvedCategoryName) {
            $relatedLocationLinks = Location::query()
                ->whereNotNull('city')
                ->whereHas('jobs', function ($q) use ($queryCategory) {
                    $q->where('status', 'published')
                        ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $queryCategory));
                })
                ->withCount(['jobs' => function ($q) use ($queryCategory) {
                    $q->where('status', 'published')
                        ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $queryCategory));
                }])
                ->orderByDesc('jobs_count')
                ->limit(8)
                ->get()
                ->map(fn ($location) => [
                    'label' => sprintf('%s di %s', $resolvedCategoryName, $location->city),
                    'url' => route('jobs.by-category-location', [
                        'category' => $queryCategory,
                        'locationSlug' => str((string) $location->city)->slug(),
                    ]),
                ]);
        }

        if ($queryLocation !== '') {
            $relatedCategoryLinks = JobCategory::query()
                ->whereHas('jobs', function ($q) use ($queryLocation) {
                    $term = '%' . $queryLocation . '%';
                    $q->where('status', 'published')
                        ->whereHas('location', function ($locationQuery) use ($term) {
                            $locationQuery->where('city', 'like', $term)
                                ->orWhere('state', 'like', $term)
                                ->orWhere('country', 'like', $term);
                        });
                })
                ->withCount(['jobs' => function ($q) use ($queryLocation) {
                    $term = '%' . $queryLocation . '%';
                    $q->where('status', 'published')
                        ->whereHas('location', function ($locationQuery) use ($term) {
                            $locationQuery->where('city', 'like', $term)
                                ->orWhere('state', 'like', $term)
                                ->orWhere('country', 'like', $term);
                        });
                }])
                ->orderByDesc('jobs_count')
                ->limit(8)
                ->get()
                ->map(fn ($category) => [
                    'label' => sprintf('%s di %s', $category->name, $queryLocation),
                    'url' => route('jobs.by-category-location', [
                        'category' => $category,
                        'locationSlug' => str($queryLocation)->slug(),
                    ]),
                ]);
        }

        $seoIntroParagraphs = collect();
        if ($resolvedCategoryName && $queryLocation !== '') {
            $seoIntroParagraphs->push(sprintf(
                'Halaman ini menampilkan lowongan kerja %s terbaru di %s dari berbagai perusahaan aktif. Kamu bisa membandingkan posisi berdasarkan gaji, pengalaman, dan tipe kerja agar proses lamaran lebih terarah.',
                $resolvedCategoryName,
                $queryLocation
            ));
            $seoIntroParagraphs->push(sprintf(
                'Tersedia %s lowongan untuk kombinasi %s di %s. Gunakan filter tambahan untuk menemukan posisi yang paling relevan dengan profil kariermu.',
                number_format($jobs->total()),
                $resolvedCategoryName,
                $queryLocation
            ));
        } elseif ($resolvedCategoryName) {
            $seoIntroParagraphs->push(sprintf(
                'Jelajahi lowongan kerja %s terbaru yang diupdate berkala dari perusahaan terverifikasi. Gunakan pencarian kata kunci dan filter pengalaman untuk menyaring hasil paling relevan.',
                $resolvedCategoryName
            ));
            $seoIntroParagraphs->push(sprintf(
                'Saat ini tersedia %s lowongan dalam kategori %s di Cari Loker.',
                number_format($jobs->total()),
                $resolvedCategoryName
            ));
        } elseif ($queryLocation !== '') {
            $seoIntroParagraphs->push(sprintf(
                'Temukan peluang karier terbaru di %s untuk berbagai level pengalaman dan bidang kerja. Halaman ini dirancang untuk memudahkanmu membandingkan lowongan secara cepat.',
                $queryLocation
            ));
            $seoIntroParagraphs->push(sprintf(
                'Ada %s lowongan di %s saat ini, termasuk opsi onsite, hybrid, dan remote.',
                number_format($jobs->total()),
                $queryLocation
            ));
        } elseif ($queryKeyword !== '') {
            $seoIntroParagraphs->push(sprintf(
                'Hasil pencarian untuk "%s" menampilkan lowongan kerja terbaru dari berbagai perusahaan. Sesuaikan lokasi dan rentang gaji untuk mendapatkan hasil yang lebih presisi.',
                $queryKeyword
            ));
            $seoIntroParagraphs->push(sprintf(
                'Ditemukan %s lowongan berdasarkan kata kunci "%s".',
                number_format($jobs->total()),
                $queryKeyword
            ));
        } else {
            $seoIntroParagraphs->push('Cari Loker membantu kandidat menemukan lowongan kerja terbaru di Indonesia dengan filter yang lengkap, mulai dari lokasi, kategori, pengalaman, hingga salary range.');
            $seoIntroParagraphs->push(sprintf('Saat ini tersedia %s lowongan yang bisa kamu telusuri dan lamar langsung.', number_format($jobs->total())));
        }

        $seoTopicLinks = collect();
        if ($resolvedCategoryName && $queryLocation !== '') {
            $resolvedCategory = $categories->firstWhere('slug', $queryCategory);
            if ($resolvedCategory) {
                $seoTopicLinks->push([
                    'label' => sprintf('Semua lowongan %s', $resolvedCategoryName),
                    'url' => route('jobs.by-category', $resolvedCategory),
                ]);
            }
            $seoTopicLinks->push([
                'label' => sprintf('Semua lowongan di %s', $queryLocation),
                'url' => route('jobs.by-location', ['locationSlug' => str($queryLocation)->slug()]),
            ]);
            $seoTopicLinks = $seoTopicLinks
                ->merge($relatedCategoryLinks->take(3))
                ->merge($relatedLocationLinks->take(3));
        } elseif ($resolvedCategoryName) {
            $seoTopicLinks = $seoTopicLinks->merge($relatedLocationLinks->take(6));
        } elseif ($queryLocation !== '') {
            $seoTopicLinks = $seoTopicLinks->merge($relatedCategoryLinks->take(6));
        } else {
            $seoTopicLinks = $seoTopicLinks
                ->merge($categories->take(4)->map(fn ($category) => [
                    'label' => sprintf('Lowongan %s', $category->name),
                    'url' => route('jobs.by-category', $category),
                ]))
                ->merge($popularLocations->take(4)->map(fn ($location) => [
                    'label' => sprintf('Lowongan di %s', $location->city),
                    'url' => route('jobs.by-location', ['locationSlug' => str((string) $location->city)->slug()]),
                ]));
        }
        $seoTopicLinks = $seoTopicLinks->unique('url')->take(8)->values();

        $seoSemanticTerms = collect([
            'lowongan kerja terbaru',
            'loker Indonesia',
            $queryKeyword !== '' ? sprintf('lowongan %s', $queryKeyword) : null,
            $resolvedCategoryName ? sprintf('karier %s', $resolvedCategoryName) : null,
            $resolvedCategoryName ? sprintf('pekerjaan %s', $resolvedCategoryName) : null,
            $queryLocation !== '' ? sprintf('loker %s', $queryLocation) : null,
            $queryLocation !== '' ? sprintf('karier di %s', $queryLocation) : null,
            ($resolvedCategoryName && $queryLocation !== '') ? sprintf('%s di %s', $resolvedCategoryName, $queryLocation) : null,
        ])->filter()->unique()->values();

        $derivedMetaTitle = 'Lowongan Kerja Terbaru - Cari Loker';
        $derivedMetaDescription = 'Cari dan temukan pekerjaan impianmu! Jelajahi ribuan lowongan kerja terbaru di berbagai bidang dan lokasi di seluruh Indonesia hanya di Cari Loker.';
        if ($queryKeyword !== '' && $queryLocation !== '') {
            $derivedMetaTitle = sprintf('Lowongan %s di %s Terbaru - Cari Loker', $queryKeyword, $queryLocation);
            $derivedMetaDescription = sprintf('Temukan lowongan %s terbaru di %s. Filter posisi, gaji, dan pengalaman sesuai profilmu.', $queryKeyword, $queryLocation);
        } elseif ($queryKeyword !== '') {
            $derivedMetaTitle = sprintf('Lowongan %s Terbaru - Cari Loker', $queryKeyword);
            $derivedMetaDescription = sprintf('Cari lowongan %s terbaru dari berbagai perusahaan terverifikasi di Indonesia.', $queryKeyword);
        } elseif ($queryLocation !== '') {
            $derivedMetaTitle = sprintf('Lowongan Kerja di %s Terbaru - Cari Loker', $queryLocation);
            $derivedMetaDescription = sprintf('Jelajahi lowongan kerja terbaru di %s dengan filter gaji, pengalaman, dan tipe pekerjaan.', $queryLocation);
        } elseif ($resolvedCategoryName) {
            $derivedMetaTitle = sprintf('Lowongan Kerja %s Terbaru - Cari Loker', $resolvedCategoryName);
            $derivedMetaDescription = sprintf('Temukan lowongan kerja %s terbaru dari perusahaan aktif di Indonesia.', $resolvedCategoryName);
        }

        return view('jobs.index', [
            'jobs' => $jobs,
            'categories' => $categories,
            'popularCompanies' => $popularCompanies,
            'educationLevels' => $educationLevels,
            'popularLocations' => $popularLocations,
            'seoSearchCombos' => $searchCombos,
            'seoMetaTitle' => $seoOverrides['metaTitle'] ?? $derivedMetaTitle,
            'seoMetaDescription' => $seoOverrides['metaDescription'] ?? $derivedMetaDescription,
            'pageHeading' => $seoOverrides['pageHeading'] ?? null,
            'pageSubheading' => $seoOverrides['pageSubheading'] ?? null,
            'breadcrumbItems' => $seoOverrides['breadcrumbItems'] ?? null,
            'seoContentTitle' => $seoOverrides['seoContentTitle'] ?? null,
            'seoContentBody' => $seoOverrides['seoContentBody'] ?? null,
            'seoFaqs' => $seoOverrides['seoFaqs'] ?? [],
            'relatedCategoryLinks' => $relatedCategoryLinks,
            'relatedLocationLinks' => $relatedLocationLinks,
            'seoIntroParagraphs' => $seoIntroParagraphs,
            'seoTopicLinks' => $seoTopicLinks,
            'seoSemanticTerms' => $seoSemanticTerms,
        ]);
    }

    /**
     * Display the alternative landing page (Beranda)
     */
    public function beranda(Request $request)
    {
        // Get categories with job counts
        $categories = Cache::remember('beranda:categories:with-count', 600, function() {
            return JobCategory::query()
                ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
                ->orderBy('name')
                ->get();
        });

        // Get popular companies with logos
        $popularCompanies = Cache::remember('beranda:companies:popular', 600, function() {
            return Company::query()
                ->whereNotNull('logo_path')
                ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
                ->orderByDesc('jobs_count')
                ->limit(8)
                ->get();
        });

        // Get featured/latest jobs
        $featuredJobs = Job::withoutGlobalScope('notExpired')
            ->with(['company', 'location', 'category'])
            ->where('status', 'published')
            ->orderByRaw("
                CASE
                    WHEN valid_until IS NOT NULL AND DATE(valid_until) < ? THEN 1
                    ELSE 0
                END ASC
            ", [now()->toDateString()])
            ->latest()
            ->limit(6)
            ->get();

        // Get top jobs (by views or most recent)
        $topJobs = Job::withoutGlobalScope('notExpired')
            ->with(['company', 'location', 'category'])
            ->where('status', 'published')
            ->orderByRaw("
                CASE
                    WHEN valid_until IS NOT NULL AND DATE(valid_until) < ? THEN 1
                    ELSE 0
                END ASC
            ", [now()->toDateString()])
            ->orderByDesc('views')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('beranda', [
            'categories' => $categories,
            'popularCompanies' => $popularCompanies,
            'featuredJobs' => $featuredJobs,
            'topJobs' => $topJobs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        $job->load(['company', 'location', 'category', 'skills']);

        $isExpired = $job->valid_until && $job->valid_until->lt(now()->startOfDay());

        // Increment views count for active listings
        if (! $isExpired) {
            $job->increment('views');
        }

        $relatedJobs = Job::query()
            ->with(['company', 'location'])
            ->where('status', 'published')
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('company_id', $job->company_id);
                if ($job->category_id) {
                    $q->orWhere('category_id', $job->category_id);
                }
            })
            ->latest()
            ->limit(6)
            ->get();

        $totalApplicants = $job->applications()->count() + (int) $job->apply_clicks;

        return view('jobs.show', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
            'totalApplicants' => $totalApplicants,
            'isExpired' => $isExpired,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function resolveCityFromSlug(string $locationSlug): ?string
    {
        $cities = Location::query()
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->get();

        return $cities
            ->first(fn ($location) => str((string) $location->city)->slug() === $locationSlug)
            ?->city;
    }
}
