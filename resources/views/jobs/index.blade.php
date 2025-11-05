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
    <div class="bg-white py-12 border-b border-gray-200 relative overflow-hidden">
        <!-- Company Logos Background (scattered) -->
        <div class="absolute inset-0 pointer-events-none opacity-20">
            <div class="relative w-full h-full">
                <!-- Twitter/X -->
                <div class="absolute top-20 left-10 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </div>
                <!-- Amazon -->
                <div class="absolute top-32 left-32 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <!-- Figma -->
                <div class="absolute top-24 right-40 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.852 8.981h-4.588V0h4.588c2.476 0 4.49 2.014 4.49 4.49s-2.014 4.491-4.49 4.491zM12.432 4.49h3.42c.818 0 1.484-.665 1.484-1.483 0-.818-.665-1.484-1.484-1.484h-3.42V4.49zm0 4.491h3.42c.818 0 1.484-.665 1.484-1.484 0-.818-.665-1.483-1.484-1.483h-3.42v2.967z"/>
                    </svg>
                </div>
                <!-- LinkedIn -->
                <div class="absolute top-40 right-20 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </div>
                <!-- Microsoft -->
                <div class="absolute bottom-32 right-32 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.4 11.4H0V0h11.4v11.4zm12.6 0H12.6V0H24v11.4zm-12.6 12.6H0V12.6h11.4V24zm12.6 0H12.6V12.6H24V24z"/>
                    </svg>
                </div>
                <!-- Google -->
                <div class="absolute top-16 left-1/3 w-12 h-12">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </div>
            </div>
        </div>

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

                <!-- CTA Buttons -->
                @if(!request()->has('list') && !request()->hasAny(['q', 'location', 'type', 'category', 'remote', 'min_salary', 'experience', 'salary_range', 'date_posted', 'work_arrangement', 'sort', 'page']))
                <div class="flex items-center justify-center gap-4 mb-12">
                    <a href="{{ route('jobs.index', ['list' => '1']) }}" class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-8 py-3.5 transition-colors text-lg">
                        Browse Jobs
                    </a>
                </div>
                @endif
            </div>
            
            <form method="GET" action="{{ route('jobs.index') }}" class="max-w-5xl mx-auto mb-8">
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
        </div>
    </div>

    @if(isset($isLandingPage) && $isLandingPage)
        <!-- Get Hired in 2 Steps Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                    {{ __('Get Hired in') }} <span class="text-violet-600">{{ __('2 Quick Easy Steps') }}</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('The quickest and most effective way to get hired by the top firm working in your career interest areas.') }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <!-- Step 1: Search Job -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 rounded-xl bg-violet-100 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 1.5rem; color: #7c3aed;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Search Job') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Browse through thousands of job opportunities that match your skills.') }}</p>
                </div>
                <!-- Step 2: Get Job -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 rounded-xl bg-yellow-100 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-briefcase" style="font-size: 1.5rem; color: #eab308;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Get Job') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Apply and get hired by your dream company. Start your new career!') }}</p>
                </div>
            </div>
        </div>

        <!-- Career Categories Section -->
        @if($categories->count() > 0)
            <div class="bg-gray-50 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                            <span class="text-violet-600">{{ __('Countless Career Options') }}</span> {{ __('Are Waiting For You to Explore') }}
                        </h2>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                        @foreach($categories->take(8) as $index => $category)
                            <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" 
                               class="bg-white rounded-2xl p-6 text-center hover:shadow-lg hover:scale-105 transition-all group {{ $index === 0 ? 'bg-violet-600 text-white' : '' }}">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-xl {{ $index === 0 ? 'bg-white/20' : 'bg-violet-50' }} flex items-center justify-center group-hover:bg-violet-100 transition-colors">
                                    <i class="fa-solid fa-briefcase text-xl {{ $index === 0 ? 'text-white' : 'text-violet-600' }} group-hover:text-violet-600"></i>
                                </div>
                                <h3 class="font-bold text-sm mb-1 {{ $index === 0 ? 'text-white' : 'text-gray-900' }} group-hover:text-violet-600">{{ $category->name }}</h3>
                                <p class="text-xs {{ $index === 0 ? 'text-white/80' : 'text-gray-500' }}">
                                    {{ $category->jobs_count ?? 0 }}+ {{ __('openings') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                    @if($categories->count() > 8)
                        <div class="text-center mt-8">
                            <a href="{{ route('jobs.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                                {{ __('View All Categories') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Latest Job Openings Section -->
        @if(isset($featuredJobs) && $featuredJobs->count() > 0)
            <div id="jobs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                        {{ __('Latest and Top Job') }} <span class="text-violet-600">{{ __('Openings') }}</span>
                    </h2>
                    <a href="{{ route('jobs.index') }}" class="text-violet-600 hover:text-violet-700 font-semibold">{{ __('View All') }} →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredJobs as $index => $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-violet-300 transition-all overflow-hidden group {{ $index === 0 ? 'md:col-span-2 lg:col-span-1 bg-violet-600 text-white' : '' }}">
                            <div class="p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    @if($job->company->logo_path)
                                        <img class="w-14 h-14 rounded-xl object-cover border {{ $index === 0 ? 'border-white/20' : 'border-gray-200' }} flex-shrink-0" 
                                             src="{{ Storage::url($job->company->logo_path) }}" 
                                             alt="{{ $job->company->name }} logo">
                                    @else
                                        <div class="w-14 h-14 rounded-xl border {{ $index === 0 ? 'border-white/20 bg-white/20' : 'border-gray-200 bg-violet-50' }} flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-xl {{ $index === 0 ? 'text-white' : 'text-violet-600' }}"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold {{ $index === 0 ? 'text-white' : 'text-gray-900' }} truncate">{{ $job->company->name }}</p>
                                        <p class="text-xs {{ $index === 0 ? 'text-white/80' : 'text-gray-500' }} mt-0.5">{{ $job->location?->city ?? __('Remote') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="block mb-3">
                                    <h3 class="text-lg font-bold {{ $index === 0 ? 'text-white' : 'text-gray-900' }} group-hover:text-violet-600 transition-colors" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $job->title }}</h3>
                                </a>
                                <p class="text-sm {{ $index === 0 ? 'text-white/90' : 'text-gray-600' }} mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ str($job->description)->limit(80) }}</p>
                                <div class="flex items-center gap-2 pt-4 border-t {{ $index === 0 ? 'border-white/20' : 'border-gray-100' }}">
                                    @if($job->external_url)
                                        <a href="{{ $job->external_url }}" target="_blank" rel="noopener" 
                                           class="flex-1 {{ $index === 0 ? 'bg-white text-violet-600 hover:bg-gray-100' : 'bg-violet-600 hover:bg-violet-700 text-white' }} font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @else
                                        <a href="{{ route('jobs.show', $job) }}" 
                                           class="flex-1 {{ $index === 0 ? 'bg-white text-violet-600 hover:bg-gray-100' : 'bg-violet-600 hover:bg-violet-700 text-white' }} font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('jobs.show', $job) }}" 
                                       class="px-4 py-2.5 border {{ $index === 0 ? 'border-white/30 text-white hover:bg-white/10' : 'border-gray-300 hover:border-violet-600 text-gray-700 hover:text-violet-600' }} font-semibold rounded-lg text-sm transition-colors">
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Main Content (Filters & Job Listings - Only show when not landing page) -->
    @if(!isset($isLandingPage) || !$isLandingPage)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:col-span-3 space-y-6">
                <!-- Filter Jobs Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('Filter Jobs') }}</h2>
                        <a href="{{ route('jobs.index') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">{{ __('Clear All') }}</a>
                    </div>
                    
                    <form id="filters" method="GET" action="{{ route('jobs.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        
                        <div class="space-y-6">
                            <!-- Salary Range -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Salary Range') }}</h3>
                                <div class="space-y-2">
                                    @php
                                        $salaryRanges = [
                                            '0-50000' => '$0-50,000',
                                            '50000-80000' => '$50,000-80,000',
                                            '80000-100000' => '$80,000-100,000',
                                            '100000-150000' => '$100,000-150,000',
                                            '150000+' => '$150,000+',
                                        ];
                                        $selectedSalary = request('salary_range');
                                    @endphp
                                    @foreach($salaryRanges as $key => $label)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="salary_range[]" value="{{ $key }}" form="filters" 
                                                   @checked(is_array($selectedSalary) && in_array($key, $selectedSalary) || $selectedSalary === $key)
                                                   onchange="document.getElementById('filters').submit()"
                                                   class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                            <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Experience -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Experience') }}</h3>
                                <div class="space-y-2">
                                    @php
                                        $experienceLevels = ['1' => '1 Year', '2' => '2 Years', '3' => '3 Years', '4' => '4 Years', '5' => '5 Years'];
                                        $selectedExp = request('experience');
                                        $selectedExpArray = is_array($selectedExp) ? $selectedExp : ($selectedExp ? [$selectedExp] : []);
                                    @endphp
                                    @foreach($experienceLevels as $key => $label)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="experience[]" value="{{ $key }}" form="filters" 
                                                   @checked(in_array($key, $selectedExpArray))
                                                   onchange="document.getElementById('filters').submit()"
                                                   class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                            <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Date Posted -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Date Posted') }}</h3>
                                <div class="space-y-2">
                                    @php
                                        $dateRanges = [
                                            'today' => __('Today'),
                                            'last_7_days' => __('Last 7 Days'),
                                            'last_15_days' => __('Last 15 Days'),
                                            'last_month' => __('Last Month'),
                                        ];
                                        $selectedDate = request('date_posted');
                                    @endphp
                                    @foreach($dateRanges as $key => $label)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="date_posted[]" value="{{ $key }}" form="filters" 
                                                   @checked(is_array($selectedDate) && in_array($key, $selectedDate) || $selectedDate === $key)
                                                   onchange="document.getElementById('filters').submit()"
                                                   class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                            <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Job Type / Work Arrangement -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Job Type') }}</h3>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="work_arrangement[]" value="onsite" form="filters" 
                                               @checked(is_array(request('work_arrangement')) && in_array('onsite', request('work_arrangement')) || request('work_arrangement') === 'onsite')
                                               onchange="document.getElementById('filters').submit()"
                                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Work From Office') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="work_arrangement[]" value="remote" form="filters" 
                                               @checked(is_array(request('work_arrangement')) && in_array('remote', request('work_arrangement')) || request('work_arrangement') === 'remote' || request('remote'))
                                               onchange="document.getElementById('filters').submit()"
                                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Work From Home') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="work_arrangement[]" value="remote_check" form="filters" 
                                               @checked(request('remote'))
                                               onchange="document.getElementById('filters').submit()"
                                               class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500 cursor-pointer">
                                        <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ __('Remote') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Upload Resume Card -->
                <div class="bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-2xl border border-violet-100 p-6">
                    <div class="text-center">
                        <i class="fa-solid fa-file-arrow-up text-3xl text-violet-600 mb-3"></i>
                        <p class="text-sm font-bold text-gray-900 mb-1">{{ __('Upload your resume') }}</p>
                        <p class="text-xs text-gray-600 mb-4">{{ __("We'll match you with the best jobs.") }}</p>
                        <a href="{{ route('register') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors">
                            {{ __('Get started') }}
                        </a>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Categories') }}</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a class="block text-sm text-gray-700 hover:text-violet-600 font-medium transition-colors py-1" 
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
                            <span class="text-violet-600">{{ number_format($jobs->total()) }}</span> 
                            <span class="text-gray-700">{{ __('results found') }}</span>
                        </p>
                    </div>
                    <form class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">{{ __('Sort by:') }}</label>
                        <div class="relative inline-block">
                            <select name="sort" onchange="this.form.submit()" 
                                    class="px-4 pr-10 py-2 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 text-gray-900 bg-white cursor-pointer appearance-none" 
                                    style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; background-color: white !important;">
                                <option value="date" @selected(request('sort')==='date')>{{ __('Date Posted') }}</option>
                                <option value="salary" @selected(request('sort')==='salary')>{{ __('Salary') }}</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none z-10">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        @foreach(request()->except(['sort','page']) as $k=>$v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                        @endforeach
                    </form>
                </div>

                <!-- Job Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-violet-300 transition-all duration-200 overflow-hidden group relative">
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
                                             alt="{{ $job->company->name }} logo">
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
                                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            @if($job->salary_max)
                                                ${{ number_format($job->salary_min / 1000) }}-{{ number_format($job->salary_max / 1000) }},000/Year
                                            @else
                                                ${{ number_format($job->salary_min / 1000) }},000/Year
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
                                    @if($job->external_url)
                                        <a href="{{ $job->external_url }}" target="_blank" rel="noopener" 
                                           class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @else
                                        <a href="{{ route('jobs.show', $job) }}" 
                                           class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                            {{ __('Apply Now') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('jobs.show', $job) }}" 
                                       class="px-4 py-2.5 border border-gray-300 hover:border-violet-600 text-gray-700 hover:text-violet-600 font-semibold rounded-lg text-sm bg-white transition-colors">
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
    @endif
</x-app-layout>
