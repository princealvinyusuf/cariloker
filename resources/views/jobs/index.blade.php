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
    <div class="bg-white py-8 border-b border-gray-200 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
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
                        @foreach($categories->take(8) as $category)
                            <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" 
                               class="bg-white rounded-2xl p-6 text-center hover:shadow-lg hover:scale-105 hover:bg-violet-50 transition-all group">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-violet-50 flex items-center justify-center group-hover:bg-violet-100 transition-colors">
                                    <i class="fa-solid fa-briefcase text-xl text-violet-600 group-hover:text-violet-600"></i>
                                </div>
                                <h3 class="font-bold text-sm mb-1 text-gray-900 group-hover:text-violet-600">{{ $category->name }}</h3>
                                <p class="text-xs text-gray-500 group-hover:text-gray-600">
                                    {{ $category->jobs_count ?? 0 }}+ {{ __('openings') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                    @if($categories->count() > 8)
                        <div class="text-center mt-8">
                            <a href="{{ route('categories.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
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
                    <a href="{{ route('jobs.index', ['list' => '1']) }}" class="text-violet-600 hover:text-violet-700 font-semibold">{{ __('View All') }} →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredJobs as $job)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-violet-300 hover:bg-violet-50 transition-all overflow-hidden group relative">
                            <div class="p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    @if($job->company->logo_path)
                                        <img class="w-14 h-14 rounded-xl object-cover border border-gray-200 flex-shrink-0" 
                                             src="{{ Storage::url($job->company->logo_path) }}" 
                                             alt="{{ $job->company->name }} logo">
                                    @else
                                        <div class="w-14 h-14 rounded-xl border border-gray-200 bg-violet-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-xl text-violet-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $job->company->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $job->location?->city ?? __('Remote') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="block mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-violet-600 transition-colors" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $job->title }}</h3>
                                </a>
                                <p class="text-sm text-gray-600 mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ str($job->description)->limit(80) }}</p>
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
                                        // Salary ranges in IDR (Rupiah)
                                        $salaryRanges = [
                                            '0-5000000' => 'Rp 0 - 5.000.000',
                                            '5000000-10000000' => 'Rp 5.000.000 - 10.000.000',
                                            '10000000-15000000' => 'Rp 10.000.000 - 15.000.000',
                                            '15000000-20000000' => 'Rp 15.000.000 - 20.000.000',
                                            '20000000+' => 'Rp 20.000.000+',
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

                <!-- Job Cards Grid -->
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
    @endif
</x-app-layout>
