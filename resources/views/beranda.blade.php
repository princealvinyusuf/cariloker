@php
    $employmentTypes = [
        'full_time' => __('Full-Time'),
        'part_time' => __('Part-Time'),
        'contract' => __('Contract'),
        'internship' => __('Internship'),
        'freelance' => __('Freelance'),
    ];

    // Category icons mapping
    $categoryIcons = [
        'sales' => ['icon' => 'fa-chart-line', 'color' => 'bg-purple-100', 'iconColor' => 'text-purple-600'],
        'marketing' => ['icon' => 'fa-bullhorn', 'color' => 'bg-red-100', 'iconColor' => 'text-red-600'],
        'finance' => ['icon' => 'fa-briefcase', 'color' => 'bg-yellow-100', 'iconColor' => 'text-yellow-600'],
        'construction' => ['icon' => 'fa-hard-hat', 'color' => 'bg-blue-100', 'iconColor' => 'text-blue-600'],
        'design' => ['icon' => 'fa-palette', 'color' => 'bg-orange-100', 'iconColor' => 'text-orange-600'],
        'logistics' => ['icon' => 'fa-truck', 'color' => 'bg-green-100', 'iconColor' => 'text-green-600'],
        'delivery' => ['icon' => 'fa-truck', 'color' => 'bg-green-100', 'iconColor' => 'text-green-600'],
        'admin' => ['icon' => 'fa-desktop', 'color' => 'bg-blue-100', 'iconColor' => 'text-blue-600'],
        'automobile' => ['icon' => 'fa-car', 'color' => 'bg-blue-100', 'iconColor' => 'text-blue-600'],
        'default' => ['icon' => 'fa-briefcase', 'color' => 'bg-gray-100', 'iconColor' => 'text-gray-600'],
    ];

    $getCategoryIcon = function($categoryName) use ($categoryIcons) {
        $name = strtolower($categoryName);
        foreach ($categoryIcons as $key => $icon) {
            if (str_contains($name, $key)) {
                return $icon;
            }
        }
        return $categoryIcons['default'];
    };

    $formatIdr = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

@section('meta_title', __('Cari Loker - Portal Lowongan Kerja Terbaru di Indonesia'))
@section('meta_description', __('beranda.meta'))
@section('og_type', 'website')

<x-app-layout>
    <section class="relative overflow-hidden border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_10%,rgba(31,123,255,0.12),transparent_40%),radial-gradient(circle_at_85%_35%,rgba(6,182,212,0.12),transparent_35%)]"></div>
        <div class="section-container relative py-16 md:py-20">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-primary-50 px-4 py-1 text-xs font-semibold text-primary-700 ring-1 ring-primary-100 dark:bg-primary-900/30 dark:text-primary-300 dark:ring-primary-700/40">
                    {{ __('beranda.badge') }}
                </span>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white md:text-6xl">
                    {{ __('beranda.headline') }} <span class="text-primary-600">{{ __('beranda.headline_highlight') }}</span>
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base text-slate-600 dark:text-slate-300 md:text-lg">
                    {{ __('beranda.subtagline') }}
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('jobs.index', ['list' => 1]) }}" class="btn-primary">
                        {{ __('beranda.cta_browse') }}
                    </a>
                    <a href="#how-it-works" class="btn-secondary">
                        {{ __('beranda.cta_how') }}
                    </a>
                </div>
            </div>

            @if($categories->count() > 0)
                <div class="mx-auto mt-10 flex max-w-6xl flex-wrap justify-center gap-2">
                    @foreach($categories->take(10) as $category)
                        <a href="{{ route('jobs.by-category', $category) }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($categories->count() > 0)
        <section class="section-container py-14">
            <div class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">{{ __('Categories') }}</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ __('Explore Jobs by Category') }}</h2>
                </div>
                <a href="{{ route('categories.index') }}" class="btn-secondary">{{ __('View All') }}</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($categories->take(8) as $category)
                    @php $icon = $getCategoryIcon($category->name); @endphp
                    <a href="{{ route('jobs.by-category', $category) }}" class="surface-card p-5 transition hover:-translate-y-0.5 hover:border-primary-300">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl {{ $icon['color'] }}">
                            <i class="fa-solid {{ $icon['icon'] }} text-lg {{ $icon['iconColor'] }}"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $category->name }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $category->jobs_count ?? 0 }} {{ __('openings') }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-container pb-14">
        @php $allJobs = $featuredJobs->merge($topJobs)->unique('id')->take(6); @endphp
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">{{ __('Featured') }}</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ __('Latest Opportunities') }}</h2>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($allJobs as $job)
                @php
                    $jobIsExpired = $job->valid_until && $job->valid_until->lt(now()->startOfDay());
                @endphp
                <article class="surface-card p-6 transition hover:-translate-y-0.5 hover:border-primary-300">
                    <div class="mb-4 flex items-start gap-3">
                        @if($job->company->logo_path)
                            <img class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo" loading="lazy">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                <i class="fa-solid fa-building"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $job->company->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $job->location?->city ?? ($job->location?->country ?? __('Remote')) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('jobs.show', $job) }}" class="block text-lg font-bold text-slate-900 transition hover:text-primary-700 dark:text-white dark:hover:text-primary-300">{{ $job->title }}</a>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-600 dark:text-slate-300">{{ str($job->plain_description_text)->limit(110) }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">{{ $employmentTypes[$job->employment_type] ?? str($job->employment_type)->replace('_', ' ')->title() }}</span>
                        @if($job->salary_min)
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $formatIdr($job->salary_min) }}{{ $job->salary_max ? ' - '.$formatIdr($job->salary_max) : '' }}
                            </span>
                        @endif
                        @if($jobIsExpired)
                            <span class="rounded-full bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                                {{ __('Expired') }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $job) }}" class="btn-primary mt-5 w-full">{{ __('View Details') }}</a>
                </article>
            @endforeach
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('jobs.index', ['list' => 1]) }}" class="btn-secondary">{{ __('View All Jobs') }}</a>
        </div>
    </section>

    <section id="how-it-works" class="border-y border-slate-200 bg-white py-14 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="section-container">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('beranda.steps_headline') }} <span class="text-primary-600">{{ __('beranda.steps_highlight') }}</span></h2>
                <p class="mx-auto mt-3 max-w-2xl text-slate-600 dark:text-slate-300">{{ __('beranda.steps_tagline') }}</p>
            </div>
            <div class="mx-auto mt-10 grid max-w-4xl gap-4 md:grid-cols-2">
                <div class="surface-card p-6 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300"><i class="fa-solid fa-magnifying-glass text-xl"></i></div>
                    <h3 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">{{ __('beranda.step2_title') }}</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('beranda.step2_desc') }}</p>
                </div>
                <div class="surface-card p-6 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300"><i class="fa-solid fa-briefcase text-xl"></i></div>
                    <h3 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">{{ __('beranda.step4_title') }}</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('beranda.step4_desc') }}</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
