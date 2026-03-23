@section('meta_title', $job->title . ' - ' . ($job->company->name ?? 'Cari Loker'))
@section('meta_description', str($job->plain_description_text)->limit(155, ''))
@section('og_type', 'article')
@if(($isExpired ?? false))
    @section('meta_robots', 'noindex,follow')
@endif
@if($job->company?->logo_path)
    @section('og_image', url(Storage::url($job->company->logo_path)))
@endif
@section('structured_data')
    @php
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => route('beranda'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Jobs',
                    'item' => route('jobs.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $job->title,
                    'item' => route('jobs.show', $job),
                ],
            ],
        ];

        $jobSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => $job->plain_description_text,
            'datePosted' => optional($job->posted_at ?? $job->created_at)->toAtomString(),
            'validThrough' => optional($job->valid_until)->toAtomString(),
            'employmentType' => str((string) $job->employment_type)->replace('_', ' ')->upper()->toString(),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $job->company?->name,
                'sameAs' => $job->company?->website_url,
                'logo' => $job->company?->logo_path ? url(Storage::url($job->company->logo_path)) : null,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $job->location?->city,
                    'addressRegion' => $job->location?->state,
                    'addressCountry' => $job->location?->country ?: 'ID',
                ],
            ],
            'directApply' => (bool) $job->external_url,
            'url' => route('jobs.show', $job),
        ];

        if ($job->salary_min) {
            $jobSchema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => $job->salary_currency ?: 'IDR',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => (float) $job->salary_min,
                    'maxValue' => (float) ($job->salary_max ?: $job->salary_min),
                    'unitText' => 'YEAR',
                ],
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @unless(($isExpired ?? false))
        <script type="application/ld+json">{!! json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endunless
@endsection

<x-app-layout>
    @if(($isExpired ?? false))
        <div id="expired-job-modal" class="fixed inset-0 flex items-center justify-center bg-slate-900/70 px-4" style="z-index: 9999;">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900">
                <div class="mb-2 flex items-center gap-2 text-amber-600 dark:text-amber-300">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p class="text-sm font-semibold uppercase tracking-wide">{{ __('Informasi') }}</p>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Lamaran ini telah expired') }}</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    {{ __('Posisi ini sudah melewati batas waktu lamaran. Kamu masih bisa membaca detail pekerjaan dan melihat rekomendasi lowongan serupa di bawah.') }}
                </p>
                <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                    <a href="#related-jobs" class="btn-primary w-full text-center">{{ __('Lihat rekomendasi lowongan') }}</a>
                    <button type="button" onclick="document.getElementById('expired-job-modal')?.remove()" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:text-primary-300">
                        {{ __('Tutup') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section class="border-b border-slate-200 bg-white py-10 dark:border-slate-800 dark:bg-slate-950">
        <div class="section-container">
            <a href="{{ route('jobs.index', ['list' => 1]) }}" class="text-sm font-medium text-primary-700 hover:text-primary-800 dark:text-primary-300">← {{ __('Back to search') }}</a>
            <div class="mt-3 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $job->title }}</h1>
                    <p class="mt-1 text-slate-600 dark:text-slate-300">
                        <a href="{{ route('companies.show', $job->company) }}" class="font-medium text-primary-700 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-200">
                            {{ $job->company->name }}
                        </a>
                        • {{ $job->location?->city ?? __('Remote') }}
                    </p>
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        @if($job->openings)
                            <span class="rounded-full bg-sky-50 px-2.5 py-1 font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">{{ $job->openings }} {{ __('Positions') }}</span>
                        @endif
                        @if($job->employment_type)
                            <span class="rounded-full bg-primary-50 px-2.5 py-1 font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</span>
                        @endif
                        @if($job->salary_min)
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">{{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - '.number_format($job->salary_max) : '' }} {{ $job->salary_currency }}</span>
                        @endif
                        @if(($isExpired ?? false))
                            <span class="rounded-full bg-rose-50 px-2.5 py-1 font-medium text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">{{ __('Expired') }}</span>
                        @endif
                    </div>
                </div>
                @if($job->external_url && !($isExpired ?? false))
                    <a href="{{ route('jobs.apply.external', $job) }}" target="_blank" rel="noopener" class="btn-primary">{{ __('Apply Now') }}</a>
                @endif
            </div>
        </div>
    </section>

    <div class="section-container grid gap-6 py-8 md:grid-cols-12">
        <main class="space-y-6 md:col-span-8">
            <div class="surface-card p-6" id="description">
                <div class="flex items-center gap-4">
                    @if($job->company->logo_path)
                        <a href="{{ route('companies.show', $job->company) }}" class="inline-block">
                            <img class="h-16 w-16 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo" loading="lazy">
                        </a>
                    @else
                        <a href="{{ route('companies.show', $job->company) }}" class="inline-block">
                            <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300"><i class="fa-solid fa-building text-2xl"></i></div>
                        </a>
                    @endif
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $job->company->name }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $job->location?->city ?? __('Remote') }}</p>
                    </div>
                </div>
                @php
                    $sanitizedHtmlDescription = $job->sanitized_description_html;
                    $descriptionHasHtml = $sanitizedHtmlDescription !== strip_tags($sanitizedHtmlDescription);
                @endphp

                <div class="prose mt-6 max-w-none">
                    @if($descriptionHasHtml)
                        {!! $sanitizedHtmlDescription !!}
                    @else
                        {!! nl2br(e($job->plain_description_text)) !!}
                    @endif
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Requirements') }}</h2>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @if($job->work_arrangement || $job->is_remote)
                        <span class="rounded-md bg-primary-50 px-2 py-1 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : __('Remote') }}</span>
                    @endif
                    @if($job->experience_min || $job->experience_max)
                        <span class="rounded-md bg-primary-50 px-2 py-1 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ $job->experience_min }} - {{ $job->experience_max }} {{ __('years experience') }}</span>
                    @endif
                    @if($job->education_level)
                        <span class="rounded-md bg-primary-50 px-2 py-1 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ $job->education_level }}</span>
                    @endif
                </div>
                @if($job->requirements)
                    @php
                        $requirementsItems = collect(preg_split('/[\r\n,;|]+/', (string) $job->requirements))
                            ->map(fn ($item) => trim((string) $item))
                            ->filter()
                            ->values();
                    @endphp
                    @if($requirementsItems->isNotEmpty())
                        <ul class="mt-4 list-disc space-y-1 pl-5 text-sm text-slate-700 dark:text-slate-200">
                            @foreach($requirementsItems as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 whitespace-pre-line text-sm text-slate-700 dark:text-slate-200">{{ $job->requirements }}</p>
                    @endif
                @endif
                <div class="mt-5 border-t border-slate-100 pt-4 text-sm dark:border-slate-800">
                    @if($job->category)
                        <a href="{{ route('jobs.by-category', $job->category) }}" class="font-medium text-primary-700 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-200">
                            {{ __('Lihat lowongan lain di kategori') }} {{ $job->category->name }} →
                        </a>
                    @endif
                    @if($job->location?->city)
                        <div class="mt-2">
                            <a href="{{ route('jobs.by-location', ['locationSlug' => \Illuminate\Support\Str::slug((string) $job->location->city)]) }}" class="font-medium text-primary-700 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-200">
                                {{ __('Lihat lowongan lain di') }} {{ $job->location->city }} →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($job->skills->count())
                <div class="surface-card p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Skills') }}</h2>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>

        <aside class="md:col-span-4">
            <div class="surface-card sticky top-24 space-y-6 p-6">
                @if(isset($job->reviews) && $job->reviews->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Review') }}</h3>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach($job->reviews as $review)
                                <li class="flex items-center gap-2 {{ $review->type === 'positive' ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">
                                    <i class="fa-solid {{ $review->type === 'positive' ? 'fa-thumbs-up' : 'fa-thumbs-down' }}"></i>
                                    <span>{{ $review->text }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(isset($job->benefits) && $job->benefits->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Benefits & Perks') }}</h3>
                        <div class="mt-3 grid grid-cols-3 gap-3 text-center">
                            @foreach($job->benefits as $benefit)
                                <div class="rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                                    <i class="{{ $benefit->icon }} text-primary-600 dark:text-primary-300"></i>
                                    <div class="mt-1 text-xs">{{ $benefit->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Company') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">
                        @if($job->company)
                            <a href="{{ route('companies.show', $job->company) }}" class="text-primary-700 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-200">
                                {{ $job->company->name }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Province') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->location?->state }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('City/Regency') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->location?->city }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Date Posted') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ optional($job->posted_at ?? $job->created_at)->format('d M Y') }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Valid Until') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ optional($job->valid_until)->format('d M Y') ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Job Type') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Bidang Pekerjaan') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->category?->name ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Sektor Pekerjaan') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->sector_text ?? $job->company?->industry ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Jumlah Kebutuhan') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->openings ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Jenis Kelamin') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->gender ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Kondisi Fisik') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->physical_condition ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Level Pekerjaan') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->seniority_level ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Pengalaman Kerja') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">
                        @if($job->experience_text)
                            {{ $job->experience_text }}
                        @elseif($job->experience_min !== null || $job->experience_max !== null)
                            {{ $job->experience_min ?? $job->experience_max }}{{ $job->experience_max !== null ? ' - '.$job->experience_max : '' }} tahun
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Education') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ $job->education_level ?? '-' }}</div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Salary') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">
                        @if($job->salary_min)
                            @php $fmtIdr = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.'); @endphp
                            {{ $fmtIdr($job->salary_min) }}{{ $job->salary_max ? ' - '.$fmtIdr($job->salary_max) : '' }}
                        @elseif($job->salary_text)
                            {{ $job->salary_text }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Views') }}</div>
                    <div class="font-medium text-slate-900 dark:text-white">{{ number_format($job->views) }}</div>
                </div>

                @if($job->external_url && !($isExpired ?? false))
                    <a href="{{ route('jobs.apply.external', $job) }}" target="_blank" rel="noopener" class="btn-primary w-full">{{ __('Apply Now') }}</a>
                @endif
            </div>
        </aside>
    </div>

    @if(isset($relatedJobs) && $relatedJobs->count())
        <div id="related-jobs" class="section-container pb-12">
            <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-white">{{ __('More like this') }}</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($relatedJobs as $related)
                    <div class="surface-card p-5">
                        <div class="flex items-start gap-4">
                            @if($related->company->logo_path)
                                <a href="{{ route('companies.show', $related->company) }}" class="inline-block">
                                    <img class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700" src="{{ Storage::url($related->company->logo_path) }}" alt="{{ $related->company->name }} logo" loading="lazy">
                                </a>
                            @else
                                <a href="{{ route('companies.show', $related->company) }}" class="inline-block">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                        <i class="fa-solid fa-building text-xl"></i>
                                    </div>
                                </a>
                            @endif
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('jobs.show', $related) }}" class="text-base font-semibold text-slate-900 hover:text-primary-700 dark:text-white dark:hover:text-primary-300">{{ $related->title }}</a>
                                        <div class="text-xs text-slate-600 dark:text-slate-300">{{ $related->company->name }} • {{ $related->location?->city ?? 'Remote' }}</div>
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px] text-slate-600 dark:text-slate-300">
                                    @if($related->employment_type)
                                        <span class="rounded-full bg-primary-50 px-2 py-0.5 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ str($related->employment_type)->replace('_',' ')->title() }}</span>
                                    @endif
                                    @if($related->salary_min)
                                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">{{ number_format($related->salary_min) }}{{ $related->salary_max ? ' - '.number_format($related->salary_max) : '' }} {{ $related->salary_currency }}</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <a href="{{ route('jobs.show', $related) }}" class="btn-primary w-full">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Floating Apply Button -->
    @if($job->external_url && !($isExpired ?? false))
        <div class="fixed bottom-6 right-6 z-50">
            <a href="{{ route('jobs.apply.external', $job) }}" target="_blank" rel="noopener" 
               class="flex items-center gap-2 rounded-full bg-primary-600 px-6 py-4 font-semibold text-white shadow-glow transition hover:bg-primary-700">
                <i class="fa-solid fa-paper-plane"></i>
                <span>{{ __('Apply Now') }}</span>
            </a>
        </div>
    @endif
</x-app-layout>
