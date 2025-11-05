@php
    $employmentTypes = [
        'full_time' => __('Full-Time'),
        'part_time' => __('Part-Time'),
        'contract' => __('Contract'),
        'internship' => __('Internship'),
        'freelance' => __('Freelance'),
    ];
@endphp

<x-app-layout>
    <!-- Hero Search Section -->
    <div class="bg-gradient-to-br from-blue-50 via-cyan-50 to-white py-12 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">{{ __('Find your dream job') }}</h1>
                <p class="text-lg text-gray-600">{{ __('Discover opportunities that match your skills and aspirations') }}</p>
            </div>
            <form method="GET" action="{{ route('jobs.index') }}" class="max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-6 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <label class="sr-only" for="q">{{ __('Search') }}</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search job title, keywords or company') }}" 
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 transition-all" />
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="sr-only" for="location">{{ __('Location') }}</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="location" name="location" value="{{ request('location') }}" placeholder="{{ __('Location (e.g. Jakarta)') }}" 
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 transition-all" />
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="sr-only" for="type">{{ __('Job Type') }}</label>
                        <div class="relative">
                            <select id="type" name="type" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 appearance-none bg-white">
                                <option value="">{{ __('Any Type') }}</option>
                                @foreach($employmentTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl px-6 py-3 transition-colors shadow-md hover:shadow-lg">
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Filter Jobs Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('Filter Jobs') }}</h2>
                        <a href="{{ route('jobs.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">{{ __('Clear All') }}</a>
                    </div>
                    
                    <form id="filters" method="GET" action="{{ route('jobs.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        
                        <div class="space-y-6">
                            <!-- Job Type -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Job Type') }}</h3>
                                <div class="space-y-2">
                                    @foreach($employmentTypes as $key => $label)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="type" value="{{ $key }}" form="filters" @checked(request('type')===$key)
                                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-sm text-gray-700 group-hover:text-blue-600">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Min Salary -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Min. Salary (IDR)') }}</label>
                                <input type="number" name="min_salary" value="{{ request('min_salary') }}" form="filters" 
                                       placeholder="e.g. 5000000"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 transition-all">
                            </div>

                            <!-- Experience -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Experience (years)') }}</label>
                                <input type="number" name="experience" value="{{ request('experience') }}" form="filters" 
                                       placeholder="e.g. 2"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 transition-all">
                            </div>

                            <!-- Remote Only -->
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="remote" value="1" form="filters" @checked(request('remote')) 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                    <span class="text-sm text-gray-700 group-hover:text-blue-600 font-medium">{{ __('Remote only') }}</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" form="filters" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2.5 transition-colors">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Upload Resume Card -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl border border-blue-100 p-6">
                    <div class="text-center">
                        <i class="fa-solid fa-file-arrow-up text-3xl text-blue-600 mb-3"></i>
                        <p class="text-sm font-bold text-gray-900 mb-1">{{ __('Upload your resume') }}</p>
                        <p class="text-xs text-gray-600 mb-4">{{ __("We'll match you with the best jobs.") }}</p>
                        <a href="{{ route('register') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors">
                            {{ __('Get started') }}
                        </a>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Categories') }}</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a class="block text-sm text-gray-700 hover:text-blue-600 font-medium transition-colors py-1" 
                               href="{{ route('jobs.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- Main Job Listings -->
            <main class="lg:col-span-9">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            <span class="text-blue-600">{{ number_format($jobs->total()) }}</span> 
                            <span class="text-gray-700">{{ __('results found') }}</span>
                        </p>
                    </div>
                    <form class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">{{ __('Sort by:') }}</label>
                        <select name="sort" onchange="this.form.submit()" 
                                class="px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 bg-white cursor-pointer">
                            <option value="date" @selected(request('sort')==='date')>{{ __('Date Posted') }}</option>
                            <option value="salary" @selected(request('sort')==='salary')>{{ __('Salary') }}</option>
                        </select>
                        @foreach(request()->except(['sort','page']) as $k=>$v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                        @endforeach
                    </form>
                </div>

                <!-- Job Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-300 transition-all duration-200 overflow-hidden group">
                            <div class="p-6">
                                <!-- Company Logo & Info -->
                                <div class="flex items-start gap-4 mb-4">
                                    @if($job->company->logo_path)
                                        <img class="w-14 h-14 rounded-xl object-cover border border-gray-200 flex-shrink-0" 
                                             src="{{ Storage::url($job->company->logo_path) }}" 
                                             alt="{{ $job->company->name }} logo">
                                    @else
                                        <div class="w-14 h-14 rounded-xl border border-gray-200 bg-gradient-to-br from-blue-50 to-cyan-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-xl text-blue-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->company->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $job->location?->city ?? __('Remote') }}</p>
                                    </div>
                                    @auth
                                        <form method="POST" action="{{ route('jobs.save', $job) }}" class="flex-shrink-0">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>

                                <!-- Job Title -->
                                <a href="{{ route('jobs.show', $job) }}" class="block mb-3 group-hover:text-blue-600 transition-colors">
                                    <h3 class="text-lg font-bold text-gray-900" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $job->title }}</h3>
                                </a>

                                <!-- Job Description -->
                                <p class="text-sm text-gray-600 mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ str($job->description)->limit(100) }}</p>

                                <!-- Job Tags -->
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    @if($job->openings)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                            {{ $job->openings }} {{ __('Positions') }}
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">
                                        {{ $employmentTypes[$job->employment_type] ?? str($job->employment_type)->replace('_', ' ')->title() }}
                                    </span>
                                    @if(!is_null($job->experience_min) || !is_null($job->experience_max))
                                        <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                            {{ $job->experience_min ?? 0 }}–{{ $job->experience_max ?? '∞' }} {{ __('Yrs') }}
                                        </span>
                                    @endif
                                    @if($job->work_arrangement || $job->is_remote)
                                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-medium">
                                            {{ $job->work_arrangement === 'onsite' ? 'WFO' : ($job->work_arrangement === 'remote' || $job->is_remote ? 'WFH' : 'Hybrid') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Salary -->
                                @if($job->salary_min)
                                    <div class="mb-4">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - ' . number_format($job->salary_max) : '' }} 
                                            <span class="text-gray-600 font-normal">{{ $job->salary_currency }}</span>
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                    @if($job->external_url)
                                        <a href="{{ $job->external_url }}" target="_blank" rel="noopener" 
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @else
                                        <a href="{{ route('jobs.show', $job) }}" 
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('jobs.show', $job) }}" 
                                       class="px-4 py-2.5 border border-gray-300 hover:border-blue-600 text-gray-700 hover:text-blue-600 font-semibold rounded-lg text-sm transition-colors">
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
                        <a href="{{ route('jobs.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>
