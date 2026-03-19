@php
    $queryCompany = trim((string) request('q'));
    $queryLocation = trim((string) request('location'));
    $queryIndustry = trim((string) request('industry'));

    $companiesMetaTitle = __('Daftar Perusahaan Terbaik - Cari Loker');
    $companiesMetaDescription = __('Jelajahi profil perusahaan terbaik dan temukan lowongan kerja dari employer terpercaya di seluruh Indonesia.');

    if ($queryIndustry !== '' && $queryLocation !== '') {
        $companiesMetaTitle = sprintf('Perusahaan %s di %s - Cari Loker', $queryIndustry, $queryLocation);
        $companiesMetaDescription = sprintf('Lihat daftar perusahaan %s terbaik di %s beserta lowongan kerja aktifnya.', $queryIndustry, $queryLocation);
    } elseif ($queryIndustry !== '') {
        $companiesMetaTitle = sprintf('Perusahaan %s Terbaik - Cari Loker', $queryIndustry);
        $companiesMetaDescription = sprintf('Temukan profil perusahaan %s terbaik dan peluang kerja terbarunya di Indonesia.', $queryIndustry);
    } elseif ($queryLocation !== '') {
        $companiesMetaTitle = sprintf('Perusahaan di %s - Cari Loker', $queryLocation);
        $companiesMetaDescription = sprintf('Jelajahi perusahaan terbaik di %s dan temukan peluang karier yang sesuai.', $queryLocation);
    } elseif ($queryCompany !== '') {
        $companiesMetaTitle = sprintf('Hasil Pencarian Perusahaan "%s" - Cari Loker', $queryCompany);
        $companiesMetaDescription = sprintf('Temukan profil perusahaan untuk kata kunci "%s" di Cari Loker.', $queryCompany);
    }

    $companiesKeywords = collect([
        'perusahaan',
        'profil perusahaan',
        'cari loker',
        $queryCompany,
        $queryLocation,
        $queryIndustry,
    ])->filter()->unique()->implode(', ');

    $companiesBreadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Beranda'), 'item' => route('beranda')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('Companies'), 'item' => route('companies.index')],
        ],
    ];

    $companiesItemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $companiesMetaTitle,
        'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
        'numberOfItems' => $companies->count(),
        'itemListElement' => $companies->values()->map(fn ($company, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('companies.show', $company),
            'name' => $company->name,
        ])->all(),
    ];
@endphp

@section('meta_title', $companiesMetaTitle)
@section('meta_description', $companiesMetaDescription)
@section('meta_keywords', $companiesKeywords)
@section('og_type', 'website')
@section('head_tags')
    @if($companies->previousPageUrl())
        <link rel="prev" href="{{ $companies->previousPageUrl() }}">
    @endif
    @if($companies->nextPageUrl())
        <link rel="next" href="{{ $companies->nextPageUrl() }}">
    @endif
@endsection
@section('structured_data')
    <script type="application/ld+json">{!! json_encode($companiesBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($companiesItemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

<x-app-layout>
    <section class="border-b border-slate-200 bg-white py-12 dark:border-slate-800 dark:bg-slate-950">
        <div class="section-container">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white md:text-5xl">{{ __('Discover Top Companies') }}</h1>
                <p class="mt-4 text-slate-600 dark:text-slate-300">{{ __('Explore leading companies and find your next career opportunity with the best employers.') }}</p>
            </div>
            <form method="GET" action="{{ route('companies.index') }}" class="surface-card mx-auto mt-8 max-w-5xl p-4 md:p-5">
                <div class="grid gap-3 md:grid-cols-12">
                    <div class="md:col-span-5">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search company name, industry...') }}" class="w-full rounded-xl border-slate-200 pl-11 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="location" name="location" value="{{ request('location') }}" placeholder="{{ __('Location (e.g. Jakarta)') }}" class="w-full rounded-xl border-slate-200 pl-11 pr-4 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="btn-primary w-full">{{ __('Search') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Main Content (Filters & Company Listings) -->
    <div class="section-container py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Filter Companies Card -->
                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Filter Companies') }}</h2>
                        <a href="{{ route('companies.index') }}" class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ __('Clear All') }}</a>
                    </div>
                    
                    <form id="filters" method="GET" action="{{ route('companies.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        
                        <div class="space-y-6">
                            <!-- Industry -->
                            @if($industries->count() > 0)
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">{{ __('Industry') }}</h3>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach($industries as $industry)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="industry" value="{{ $industry }}" form="filters" 
                                                       @checked(request('industry') === $industry)
                                                       onchange="document.getElementById('filters').submit()"
                                                       class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500 cursor-pointer">
                                                <span class="text-sm text-slate-700 dark:text-slate-300 group-hover:text-primary-600">{{ $industry }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Company Size -->
                            @if($sizes->count() > 0)
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">{{ __('Company Size') }}</h3>
                                    <div class="space-y-2">
                                        @foreach($sizes as $size)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="size" value="{{ $size }}" form="filters" 
                                                       @checked(request('size') === $size)
                                                       onchange="document.getElementById('filters').submit()"
                                                       class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500 cursor-pointer">
                                                <span class="text-sm text-slate-700 dark:text-slate-300 group-hover:text-primary-600">{{ $size }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Browse Jobs Card -->
                <div class="surface-card p-6">
                    <div class="text-center">
                        <i class="fa-solid fa-briefcase text-3xl text-primary-600 mb-3"></i>
                        <p class="text-sm font-bold text-slate-900 dark:text-white mb-1">{{ __('Looking for jobs?') }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mb-4">{{ __('Browse thousands of job opportunities.') }}</p>
                        <a href="{{ route('jobs.index', ['list' => '1']) }}" class="btn-primary">
                            {{ __('Browse Jobs') }}
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Company Listings -->
            <main class="lg:col-span-9">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                            <span class="text-primary-600">{{ number_format($companies->total()) }}</span>
                            <span class="text-slate-700 dark:text-slate-300">{{ __('companies found') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Company Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($companies as $company)
                        <div class="surface-card overflow-hidden transition hover:-translate-y-0.5 hover:border-primary-300">
                            <div class="p-6">
                                <!-- Company Logo & Info -->
                                <div class="flex items-start gap-4 mb-4">
                                    @if($company->logo_path)
                                        <img class="w-16 h-16 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 flex-shrink-0"
                                             src="{{ Storage::url($company->logo_path) }}" 
                                             alt="{{ $company->name }} logo" loading="lazy">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-2xl text-slate-500 dark:text-slate-300"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('companies.show', $company) }}" class="block transition-colors">
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $company->name }}</h3>
                                        </a>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $company->location?->city ?? ($company->location?->country ?? __('Location not specified')) }}</p>
                                        @if($company->industry)
                                            <span class="inline-block mt-2 rounded-full bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">
                                                {{ $company->industry }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Company Description -->
                                @if($company->description)
                                    <p class="mb-4 text-sm text-slate-600 dark:text-slate-300 line-clamp-2">
                                        {{ str($company->description)->limit(100) }}
                                    </p>
                                @endif

                                <!-- Company Info -->
                                <div class="mb-4 flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                                    @if($company->size)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-users"></i>
                                            <span>{{ $company->size }}</span>
                                        </span>
                                    @endif
                                    @if($company->founded_year)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-calendar"></i>
                                            <span>{{ $company->founded_year }}</span>
                                        </span>
                                    @endif
                                    @if($company->jobs_count > 0)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-briefcase"></i>
                                            <span>{{ $company->jobs_count }} {{ __('Openings') }}</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
                                    <a href="{{ route('companies.show', $company) }}" 
                                       class="btn-primary flex-1">
                                        {{ __('View Company') }}
                                    </a>
                                    @if($company->website_url)
                                        <a href="{{ $company->website_url }}" target="_blank" rel="noopener" 
                                           class="btn-secondary !px-4 !py-2.5">
                                            <i class="fa-solid fa-external-link"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($companies->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $companies->links() }}
                    </div>
                @endif

                <!-- Empty State -->
                @if($companies->isEmpty())
                    <div class="surface-card p-12 text-center">
                        <i class="fa-solid fa-building mb-4 text-4xl text-slate-300 dark:text-slate-600"></i>
                        <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">{{ __('No companies found') }}</h3>
                        <p class="mb-6 text-slate-600 dark:text-slate-300">{{ __('Try adjusting your filters or search terms') }}</p>
                        <a href="{{ route('companies.index') }}" class="btn-primary">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>

