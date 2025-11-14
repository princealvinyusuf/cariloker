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

@section('meta_description', __('beranda.meta'))

<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-white py-16 md:py-20 border-b border-gray-200 relative overflow-hidden">
        <!-- Floating Company Logos Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            @if($popularCompanies->count() > 0)
                @foreach($popularCompanies->take(6) as $index => $company)
                    @php
                        $positions = [
                            ['top' => '10%', 'left' => '5%', 'opacity' => '0.3'],
                            ['top' => '20%', 'right' => '8%', 'opacity' => '0.25'],
                            ['top' => '50%', 'left' => '3%', 'opacity' => '0.2'],
                            ['top' => '60%', 'right' => '5%', 'opacity' => '0.3'],
                            ['top' => '80%', 'left' => '10%', 'opacity' => '0.25'],
                            ['top' => '30%', 'right' => '15%', 'opacity' => '0.2'],
                        ];
                        $pos = $positions[$index % count($positions)] ?? $positions[0];
                    @endphp
                    <div class="absolute transform hover:scale-110 transition-transform" style="top: {{ $pos['top'] }}; {{ isset($pos['left']) ? 'left: ' . $pos['left'] : 'right: ' . $pos['right'] }}; opacity: {{ $pos['opacity'] }};">
                        @if($company->logo_path)
                            <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="w-16 h-16 object-contain filter grayscale">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-100 to-fuchsia-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-violet-600">{{ substr($company->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <div class="inline-block mb-4">
                    <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        {{ __('beranda.badge') }}
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ __('beranda.headline') }} <span class="text-violet-600">{{ __('beranda.headline_highlight') }}</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    {{ __('beranda.subtagline') }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                    <a href="{{ route('jobs.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-8 py-3.5 transition-colors text-lg shadow-lg hover:shadow-xl">
                        {{ __('beranda.cta_browse') }}
                    </a>
                    <a href="#how-it-works" class="bg-white border-2 border-violet-600 text-violet-600 hover:bg-violet-50 font-semibold rounded-lg px-8 py-3.5 transition-colors text-lg flex items-center gap-2">
                        <i class="fa-solid fa-play-circle"></i>
                        {{ __('beranda.cta_how') }}
                    </a>
                </div>
            </div>

            <!-- Scrollable Job Category Tags -->
            @if($categories->count() > 0)
                <div class="overflow-x-auto pb-4 -mx-4 px-4">
                    <div class="flex gap-3 min-w-max">
                        @foreach($categories->take(12) as $category)
                            <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" 
                               class="px-6 py-2.5 rounded-full border-2 border-violet-600 text-violet-600 bg-white hover:bg-violet-50 transition-colors text-sm font-medium whitespace-nowrap {{ $loop->index == 1 || $loop->index == 5 ? 'bg-violet-600 text-white border-violet-600' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Job Categories Grid Section -->
    @if($categories->count() > 0)
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                        <span class="text-violet-600">{{ __('Countless Career Options') }}</span><br>
                        <span class="text-gray-900">{{ __('Are Waiting For You to Explore') }}</span>
                    </h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach($categories->take(8) as $index => $category)
                        @php
                            $icon = $getCategoryIcon($category->name);
                            $isHighlighted = $index == 0; // First category highlighted
                        @endphp
                        <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" 
                           class="bg-white rounded-2xl p-6 shadow-sm border-2 {{ $isHighlighted ? 'border-violet-600 bg-violet-600 text-white' : 'border-gray-200 hover:border-violet-300 hover:shadow-lg' }} transition-all duration-200 text-center group">
                            <div class="w-16 h-16 rounded-xl {{ $isHighlighted ? 'bg-white' : $icon['color'] }} flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid {{ $icon['icon'] }} text-2xl {{ $isHighlighted ? $icon['iconColor'] : $icon['iconColor'] }}"></i>
                            </div>
                            <h3 class="font-bold text-lg mb-1 {{ $isHighlighted ? 'text-white' : 'text-gray-900' }}">{{ $category->name }}</h3>
                            <p class="text-sm {{ $isHighlighted ? 'text-violet-100' : 'text-gray-600' }}">
                                {{ $category->jobs_count ?? 0 }}+ {{ __('openings') }}
                            </p>
                        </a>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('categories.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-8 py-3 transition-colors shadow-lg hover:shadow-xl">
                        {{ __('View All Categories') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Latest and Top Job Openings Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                    <span class="text-violet-600">{{ __('Latest and Top Job') }}</span><br>
                    <span class="text-gray-900">{{ __('Openings') }}</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto mt-4">
                    {{ __('Discover the fresh job openings from the giant firms in which you might want to apply and take a chance to get hired by top fortune companies.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @php
                    $allJobs = $featuredJobs->merge($topJobs)->unique('id')->take(6);
                @endphp
                @foreach($allJobs as $index => $job)
                    @php
                        $isHighlighted = $index == 0; // First job highlighted
                    @endphp
                    <div class="bg-white rounded-2xl p-6 shadow-sm border-2 {{ $isHighlighted ? 'border-violet-600 bg-violet-600' : 'border-gray-200 hover:border-violet-300 hover:shadow-lg' }} transition-all duration-200 group">
                        <div class="flex items-start gap-4 mb-4">
                            @if($job->company->logo_path)
                                <img class="w-14 h-14 rounded-full object-cover border-2 {{ $isHighlighted ? 'border-white' : 'border-gray-200' }} flex-shrink-0" 
                                     src="{{ Storage::url($job->company->logo_path) }}" 
                                     alt="{{ $job->company->name }} logo" loading="lazy">
                            @else
                                <div class="w-14 h-14 rounded-full border-2 {{ $isHighlighted ? 'border-white bg-white' : 'border-gray-200 bg-gradient-to-br from-violet-50 to-fuchsia-50' }} flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-building text-lg {{ $isHighlighted ? 'text-violet-600' : 'text-violet-600' }}"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold {{ $isHighlighted ? 'text-white' : 'text-gray-900' }} truncate">{{ $job->company->name }}</p>
                                <p class="text-xs {{ $isHighlighted ? 'text-violet-100' : 'text-gray-500' }} mt-0.5">
                                    {{ $job->location?->city ?? ($job->location?->country ?? __('Remote')) }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('jobs.show', $job) }}" class="block mb-2 group-hover:text-violet-600 transition-colors">
                            <h3 class="text-lg font-bold {{ $isHighlighted ? 'text-white' : 'text-gray-900' }} mb-2 line-clamp-2">{{ $job->title }}</h3>
                        </a>
                        <p class="text-sm {{ $isHighlighted ? 'text-violet-100' : 'text-gray-600' }} mb-4 line-clamp-2">
                            {{ str($job->description)->stripTags()->limit(100) }}
                        </p>
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($job->openings)
                                <span class="px-2.5 py-1 rounded-full {{ $isHighlighted ? 'bg-white text-violet-600' : 'bg-blue-100 text-blue-700' }} text-xs font-medium">
                                    {{ $job->openings }} {{ $job->openings == 1 ? __('Position') : __('Positions') }}
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-full {{ $isHighlighted ? 'bg-white text-violet-600' : 'bg-orange-500 text-white' }} text-xs font-medium">
                                {{ $employmentTypes[$job->employment_type] ?? str($job->employment_type)->replace('_', ' ')->title() }}
                            </span>
                                    @if($job->salary_min)
                                <span class="px-2.5 py-1 rounded-full {{ $isHighlighted ? 'bg-white text-violet-600' : 'bg-green-100 text-green-700' }} text-xs font-medium">
                                    @if($job->salary_max)
                                        {{ $formatIdr($job->salary_min) }}-{{ $formatIdr($job->salary_max) }}/{{ __('year') }}
                                    @else
                                        {{ $formatIdr($job->salary_min) }}/{{ __('year') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('jobs.show', $job) }}" 
                           class="block w-full {{ $isHighlighted ? 'bg-white text-violet-600 hover:bg-violet-50' : 'bg-violet-600 hover:bg-violet-700 text-white' }} font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                            {{ __('View Details') }}
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('jobs.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-8 py-3 transition-colors shadow-lg hover:shadow-xl">
                    {{ __('View All Jobs') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Get Hired in 4 Quick Easy Steps Section -->
    <div class="bg-gray-50 py-16" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                    {{ __('beranda.steps_headline') }} <span class="text-violet-600">{{ __('beranda.steps_highlight') }}</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('beranda.steps_tagline') }}
                </p>
            </div>
            <div class="relative">
                <!-- Connecting Dotted Line -->
                <div class="hidden lg:block absolute left-0 right-0 h-0.5 border-t-2 border-dashed border-violet-300" style="top: 6rem;"></div>
                <!-- Arrow at the end -->
                <div class="hidden lg:block absolute text-violet-600" style="top: 5.5rem; right: -1rem;">
                    <i class="fa-solid fa-paper-plane text-3xl transform rotate-45"></i>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto relative">
                    <!-- Step 1: Create an Account -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow relative z-10">
                        <div class="w-16 h-16 rounded-xl bg-orange-100 flex items-center justify-center mb-4 mx-auto">
                            <i class="fa-solid fa-user-plus text-2xl" style="color: #fb923c;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">{{ __('beranda.step1_title') }}</h3>
                        <p class="text-gray-600 text-sm text-center">{{ __('beranda.step1_desc') }}</p>
                    </div>

                    <!-- Step 2: Search Job -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow relative z-10">
                        <div class="w-16 h-16 rounded-xl bg-violet-100 flex items-center justify-center mb-4 mx-auto">
                            <i class="fa-solid fa-magnifying-glass text-2xl" style="color: #7c3aed;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">{{ __('beranda.step2_title') }}</h3>
                        <p class="text-gray-600 text-sm text-center">{{ __('beranda.step2_desc') }}</p>
                    </div>

                    <!-- Step 3: Upload CV/Resume -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow relative z-10">
                        <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center mb-4 mx-auto">
                            <i class="fa-solid fa-file-arrow-up text-2xl" style="color: #2563eb;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">{{ __('beranda.step3_title') }}</h3>
                        <p class="text-gray-600 text-sm text-center">{{ __('beranda.step3_desc') }}</p>
                    </div>

                    <!-- Step 4: Get Job -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow relative z-10">
                        <div class="w-16 h-16 rounded-xl bg-yellow-100 flex items-center justify-center mb-4 mx-auto">
                            <i class="fa-solid fa-briefcase text-2xl" style="color: #eab308;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">{{ __('beranda.step4_title') }}</h3>
                        <p class="text-gray-600 text-sm text-center">{{ __('beranda.step4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
