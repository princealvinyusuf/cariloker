@php
    $employmentTypes = [
        'full_time' => __('Full-Time'),
        'part_time' => __('Part-Time'),
        'contract' => __('Contract'),
        'internship' => __('Internship'),
        'freelance' => __('Freelance'),
    ];

    $seoMetaTitle = $seoMetaTitle ?? __('Lowongan Kerja Terbaru - Cari Loker');
    $seoMetaDescription = $seoMetaDescription ?? __('Cari dan temukan pekerjaan impianmu! Jelajahi ribuan lowongan kerja terbaru di berbagai bidang dan lokasi di seluruh Indonesia hanya di Cari Loker.');
    $pageHeading = $pageHeading ?? __('Search, Apply & Get Your Dream Job');
    $pageSubheading = $pageSubheading ?? __('Cari lowongan kerja terbaru dari berbagai bidang dan lokasi, lalu lamar dalam beberapa langkah.');

    $breadcrumbItems = $breadcrumbItems ?? [
        ['name' => __('Beranda'), 'url' => route('beranda')],
        ['name' => __('Jobs'), 'url' => route('jobs.index')],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbItems)->values()->map(function ($item, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        })->all(),
    ];

    $seoFaqs = collect($seoFaqs ?? [])->filter(fn ($faq) => !empty($faq['question']) && !empty($faq['answer']))->values();
    $seoSearchCombos = collect($seoSearchCombos ?? []);

    $metaKeywordParts = collect([
        'lowongan kerja',
        'cari loker',
        request('q'),
        request('location'),
        request('category'),
    ])->filter()->map(fn ($item) => trim((string) $item))->unique()->values();

    $faqSchema = $seoFaqs->isNotEmpty() ? [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $seoFaqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer'],
            ],
        ])->all(),
    ] : null;
@endphp

@section('meta_title', $seoMetaTitle)
@section('meta_description', $seoMetaDescription)
@section('meta_keywords', $metaKeywordParts->implode(', '))
@section('og_type', 'website')
@section('structured_data')
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if($faqSchema)
        <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endsection

<x-app-layout>
    <section class="border-b border-slate-200 bg-white py-12 dark:border-slate-800 dark:bg-slate-950">
        <div class="section-container">
            <nav aria-label="Breadcrumb" class="mb-6">
                <ol class="flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    @foreach($breadcrumbItems as $crumb)
                        <li class="flex items-center gap-2">
                            <a href="{{ $crumb['url'] }}" class="hover:text-primary-700 dark:hover:text-primary-300">{{ $crumb['name'] }}</a>
                            @unless($loop->last)<span>/</span>@endunless
                        </li>
                    @endforeach
                </ol>
            </nav>
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white md:text-5xl">{{ $pageHeading }}</h1>
                <p class="mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-300">{{ $pageSubheading }}</p>
            </div>
            <div id="job-search-bar" class="mx-auto mt-8 max-w-5xl">
                <form method="GET" action="{{ route('jobs.index') }}" class="search-bar-wrapper surface-card p-4 md:p-5">
                    <div class="grid gap-3 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search job title, keywords or company') }}" class="w-full rounded-xl border-slate-200 pl-11 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="md:col-span-5">
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="location" name="location" value="{{ request('location') }}" placeholder="{{ __('Location (e.g. Jakarta)') }}" class="w-full rounded-xl border-slate-200 pl-11 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="btn-primary w-full">{{ __('Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            @if($categories->count() > 0)
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    @foreach($categories->take(8) as $category)
                        <a href="{{ route('jobs.by-category', $category) }}" class="rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if(isset($educationLevels) && $educationLevels->count() > 0)
                <div class="mt-5">
                    <p class="mb-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('Education Level') }}</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($educationLevels as $level)
                            <a href="{{ route('jobs.index', array_merge(request()->except(['page', 'education_level']), ['education_level' => $level, 'list' => '1'])) }}" class="rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                {{ $level }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="section-container py-8">
        <div class="grid gap-6 lg:grid-cols-12">
            <aside class="hidden lg:col-span-3 lg:block">
                <div class="surface-card p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Filter Jobs') }}</h2>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ __('Clear') }}</a>
                    </div>
                    @include('jobs.partials.filters-form', ['formId' => 'filters-desktop'])
                </div>
            </aside>

            <main class="lg:col-span-9">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        <span class="font-bold text-primary-700 dark:text-primary-300">{{ number_format($jobs->total()) }}</span> {{ __('results found') }}
                    </p>
                    <div class="flex w-full items-center gap-2 sm:w-auto">
                        <div class="relative w-full sm:hidden" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ __('Filter Jobs') }}</button>
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-40 mt-2 w-full rounded-2xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                                @include('jobs.partials.filters-form', ['formId' => 'filters-mobile'])
                            </div>
                        </div>

                        <form class="flex items-center gap-2">
                            <label class="text-sm text-slate-500 dark:text-slate-400">{{ __('Sort by') }}</label>
                            <select name="sort" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                <option value="date" @selected(request('sort')==='date')>{{ __('Date Posted') }}</option>
                                <option value="salary" @selected(request('sort')==='salary')>{{ __('Salary') }}</option>
                            </select>
                            @foreach(request()->except(['sort','page']) as $k=>$v)
                                @if(is_array($v))
                                    @foreach($v as $item)
                                        <input type="hidden" name="{{ $k }}[]" value="{{ $item }}" />
                                    @endforeach
                                @elseif(!is_null($v))
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                @endif
                            @endforeach
                        </form>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($jobs as $job)
                        <article class="surface-card relative p-5 transition hover:-translate-y-0.5 hover:border-primary-300">
                            @auth
                                <form method="POST" action="{{ route('jobs.save', $job) }}" class="absolute right-4 top-4">
                                    @csrf
                                    <button type="submit" class="text-slate-400 transition hover:text-rose-500"><i class="fa-regular fa-heart text-lg"></i></button>
                                </form>
                            @endauth
                            <div class="mb-4 flex items-start gap-3">
                                @if($job->company->logo_path)
                                    <img class="h-11 w-11 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo" loading="lazy">
                                @else
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300"><i class="fa-solid fa-building"></i></div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $job->company->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $job->location?->city ?? ($job->location?->country ?? __('Remote')) }}</p>
                                </div>
                            </div>

                            <a href="{{ route('jobs.show', $job) }}" class="line-clamp-2 text-lg font-bold text-slate-900 transition hover:text-primary-700 dark:text-white dark:hover:text-primary-300">{{ $job->title }}</a>
                            <p class="mt-2 line-clamp-2 text-sm text-slate-600 dark:text-slate-300">{{ str($job->description)->limit(90) }}</p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @if($job->salary_min)
                                    @php $formatIdr = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.'); @endphp
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                        {{ $formatIdr($job->salary_min) }}{{ $job->salary_max ? ' - '.$formatIdr($job->salary_max) : '' }}
                                    </span>
                                @endif
                                <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">
                                    {{ $employmentTypes[$job->employment_type] ?? str($job->employment_type)->replace('_', ' ')->title() }}
                                </span>
                            </div>

                            <a href="{{ route('jobs.show', $job) }}" class="btn-primary mt-5 w-full">{{ __('View Details') }}</a>
                        </article>
                    @endforeach
                </div>

                @if($jobs->hasPages())
                    <div class="mt-8 flex justify-center">{{ $jobs->links() }}</div>
                @endif

                @if($jobs->isEmpty())
                    <div class="surface-card p-12 text-center">
                        <i class="fa-solid fa-briefcase text-4xl text-slate-300 dark:text-slate-600"></i>
                        <h3 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">{{ __('No jobs found') }}</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">{{ __('Try adjusting your filters or search terms') }}</p>
                        <a href="{{ route('jobs.index') }}" class="btn-primary mt-6">{{ __('Clear Filters') }}</a>
                    </div>
                @endif
            </main>
        </div>
    </section>

    <section class="section-container pb-12">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="surface-card p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Explore by Category') }}</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($categories->take(10) as $category)
                        <a href="{{ route('jobs.by-category', $category) }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="surface-card p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Explore by Location') }}</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(($popularLocations ?? collect())->take(10) as $location)
                        <a href="{{ route('jobs.by-location', ['locationSlug' => \Illuminate\Support\Str::slug((string) $location->city)]) }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            {{ $location->city }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($seoSearchCombos->isNotEmpty())
        <section class="section-container pb-12">
            <div class="surface-card p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Popular Job Searches') }}</h2>
                <p class="mt-2 text-slate-600 dark:text-slate-300">{{ __('Halaman pencarian populer untuk membantu kamu menemukan lowongan berdasarkan kategori dan kota.') }}</p>
                <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($seoSearchCombos as $combo)
                        <a href="{{ $combo['url'] }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            {{ $combo['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(!empty($seoContentTitle) || !empty($seoContentBody) || $seoFaqs->isNotEmpty())
        <section class="section-container pb-12">
            <div class="surface-card p-6 md:p-8">
                @if(!empty($seoContentTitle))
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $seoContentTitle }}</h2>
                @endif
                @if(!empty($seoContentBody))
                    <p class="mt-3 text-slate-600 dark:text-slate-300">{{ $seoContentBody }}</p>
                @endif

                @if($seoFaqs->isNotEmpty())
                    <div class="mt-6 space-y-3">
                        @foreach($seoFaqs as $faq)
                            <details class="rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                                <summary class="cursor-pointer text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $faq['question'] }}
                                </summary>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $faq['answer'] }}</p>
                            </details>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
</x-app-layout>
