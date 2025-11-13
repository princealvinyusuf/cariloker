@php
    $employmentTypes = [
        'full_time' => __('Full-Time'),
        'part_time' => __('Part-Time'),
        'contract' => __('Contract'),
        'internship' => __('Internship'),
        'freelance' => __('Freelance'),
    ];
@endphp

@section('meta_description', __('Cari dan temukan pekerjaan impianmu! Jelajahi ribuan lowongan kerja terbaru di berbagai bidang dan lokasi di seluruh Indonesia hanya di Cari Loker.'))

<x-app-layout>
    <!-- Hero Search Section -->
    <div class="bg-white py-12 border-b border-gray-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-8">
                <!-- Badge -->
                <div class="inline-block mb-4">
                    <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        No.1 Cari Loker Website
                    </span>
                </div>
                <!-- Headline -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ __('Search, Apply & Get Your') }}<br>
                    <span class="text-violet-600">{{ __('Dream Job') }}</span>
                </h1>
                <!-- Tagline -->
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    {{ __('Start your hunt for the best, life-changing career opportunities from here in your selected areas conveniently and get hired quickly.') }}
                </p>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div id="job-search-bar" class="max-w-5xl mx-auto mb-8">
                <form method="GET" action="{{ route('jobs.index') }}" class="search-bar-wrapper">
                    <div class="bg-white rounded-2xl shadow-lg p-6 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4">
                        <label class="sr-only" for="q">{{ __('Search') }}</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search job title, keywords or company') }}" 
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all" />
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="sr-only" for="location">{{ __('Location') }}</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="location" name="location" value="{{ request('location') }}" placeholder="{{ __('Location (e.g. Jakarta)') }}" 
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 transition-all" />
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="sr-only" for="type">{{ __('Job Type') }}</label>
                        <div class="relative">
                            <select id="type" name="type" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 bg-white cursor-pointer appearance-none" style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; background-color: white !important;">
                                <option value="">{{ __('Any Type') }}</option>
                                @foreach($employmentTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none z-10">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl px-4 py-3 transition-colors shadow-md hover:shadow-lg whitespace-nowrap">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Job Category Tags -->
            @if($categories->count() > 0)
                <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
                    @foreach($categories->take(8) as $category)
                        <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" 
                           class="px-4 py-2 rounded-full border border-violet-600 text-violet-600 bg-white hover:bg-violet-50 transition-colors text-sm font-medium">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            @if(isset($educationLevels) && $educationLevels->count() > 0)
                <div class="mt-6">
                    <div class="text-center text-sm font-semibold text-gray-600 mb-3">{{ __('Education Level') }}</div>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @foreach($educationLevels as $level)
                            <a href="{{ route('jobs.index', array_merge(request()->except(['page', 'education_level']), ['education_level' => $level, 'list' => '1'])) }}"
                               class="px-4 py-2 rounded-full border border-violet-500 text-violet-600 bg-white hover:bg-violet-50 transition-colors text-sm font-medium">
                                {{ $level }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
            </div>
    <!-- Main Content: Filters & Job Listings -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:col-span-3 space-y-6 hidden lg:block">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('Filter Jobs') }}</h2>
                        <a href="{{ route('jobs.index') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">{{ __('Clear All') }}</a>
                    </div>
                    @include('jobs.partials.filters-form', ['formId' => 'filters-desktop'])
                </div>
                </aside>
            <!-- Main Job Listings -->
            <main class="lg:col-span-9">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            <span class="text-violet-600">{{ number_format($jobs->total()) }}</span> 
                            <span class="text-gray-700">{{ __('results found') }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:hidden" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                <span>{{ __('Filter Jobs') }}</span>
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-40">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-base font-semibold text-gray-900">{{ __('Filter Jobs') }}</h3>
                                    <a href="{{ route('jobs.index') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">{{ __('Clear All') }}</a>
                                </div>
                                @include('jobs.partials.filters-form', ['formId' => 'filters-mobile'])
                            </div>
                        </div>
                        <form class="flex items-center gap-2 w-full sm:w-auto">
                            <label class="text-sm text-gray-600">{{ __('Sort by:') }}</label>
                            <div class="relative inline-block flex-1 sm:flex-initial">
                                <select name="sort" onchange="this.form.submit()" class="w-full px-4 pr-10 py-2 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 bg-white cursor-pointer appearance-none" style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; background-color: white !important;">
                                    <option value="date" @selected(request('sort')==='date')>{{ __('Date Posted') }}</option>
                                    <option value="salary" @selected(request('sort')==='salary')>{{ __('Salary') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none z-10">
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
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
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-violet-300 hover:bg-violet-50 transition-all duration-200 overflow-hidden group relative">
                            <div class="p-6">
                                <!-- Favorite Icon - Top Right -->
                                @auth
                                    <form method="POST" action="{{ route('jobs.save', $job) }}" class="absolute top-4 right-4">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                            <i class="fa-regular fa-heart text-lg"></i>
                                        </button>
                                    </form>
                                @endauth
                                <!-- Company Logo & Info -->
                                <div class="flex items-start gap-3 mb-4">
                                    @if($job->company->logo_path)
                                        <img class="w-12 h-12 rounded-full object-cover border border-gray-200 flex-shrink-0" 
                                             src="{{ Storage::url($job->company->logo_path) }}" 
                                             alt="{{ $job->company->name }} logo" loading="lazy">
                                    @else
                                        <div class="w-12 h-12 rounded-full border border-gray-200 bg-gradient-to-br from-violet-50 to-fuchsia-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-lg text-violet-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->company->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $job->location?->city ?? ($job->location?->country ?? __('Remote')) }}</p>
                                    </div>
                                </div>
                                <!-- Job Title -->
                                <a href="{{ route('jobs.show', $job) }}" class="block mb-2 group-hover:text-violet-600 transition-colors">
                                    <h3 class="text-lg font-bold text-gray-900" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $job->title }}</h3>
                                </a>
                                <!-- Job Description -->
                                <p class="text-sm text-gray-600 mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ str($job->description)->limit(80) }}</p>
                                <!-- Job Tags -->
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    @if($job->openings)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                            {{ $job->openings }} {{ $job->openings == 1 ? __('Position') : __('Positions') }}
                                        </span>
                                    @endif
                                    @if($job->salary_min)
                                        @php
                                            $formatIdr = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            @if($job->salary_max)
                                                {{ $formatIdr($job->salary_min) }} - {{ $formatIdr($job->salary_max) }}
                                            @else
                                                {{ $formatIdr($job->salary_min) }}
                                            @endif
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-1 rounded-full bg-orange-500 text-white text-xs font-medium">
                                        {{ $employmentTypes[$job->employment_type] ?? str($job->employment_type)->replace('_', ' ')->title() }}
                                    </span>
                                    @if(!is_null($job->experience_min) || !is_null($job->experience_max))
                                        @php
                                            $expText = ($job->experience_min ?? 0);
                                            if ($job->experience_max && $job->experience_max != $job->experience_min) {
                                                $expText .= '-' . $job->experience_max;
                                            }
                                            $expText .= ($job->experience_max == 1 || $job->experience_min == 1) ? ' Year' : ' Years';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full bg-green-600 text-white text-xs font-medium">
                                            {{ $expText }}
                                        </span>
                                    @endif
                                    @if($job->work_arrangement || $job->is_remote)
                                        <span class="px-2.5 py-1 rounded-full bg-red-500 text-white text-xs font-medium">
                                            {{ $job->work_arrangement === 'onsite' ? 'WFO' : ($job->work_arrangement === 'remote' || $job->is_remote ? 'WFH' : 'Hybrid') }}
                                        </span>
                                    @endif
                                </div>
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                    <a href="{{ route('jobs.show', $job) }}" 
                                       class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                @if($jobs->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $jobs->links() }}
                    </div>
                @endif
                <!-- Empty State -->
                @if($jobs->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <i class="fa-solid fa-briefcase text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No jobs found') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('Try adjusting your filters or search terms') }}</p>
                        <a href="{{ route('jobs.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>
