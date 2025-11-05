<x-app-layout>
    <!-- Hero Search Section -->
    <div class="bg-white py-12 border-b border-gray-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-8">
                <!-- Badge -->
                <div class="inline-block mb-4">
                    <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        {{ __('Browse Companies') }}
                    </span>
                </div>
                
                <!-- Headline -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ __('Discover Top') }}<br>
                    <span class="text-violet-600">{{ __('Companies') }}</span>
                </h1>
                
                <!-- Tagline -->
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    {{ __('Explore leading companies and find your next career opportunity with the best employers.') }}
                </p>
            </div>
            
            <form method="GET" action="{{ route('companies.index') }}" class="max-w-5xl mx-auto mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <label class="sr-only" for="q">{{ __('Search') }}</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search company name, industry...') }}" 
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
                    <div class="md:col-span-3">
                        <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl px-6 py-3 transition-colors shadow-md hover:shadow-lg">
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content (Filters & Company Listings) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Filter Companies Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">{{ __('Filter Companies') }}</h2>
                        <a href="{{ route('companies.index') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">{{ __('Clear All') }}</a>
                    </div>
                    
                    <form id="filters" method="GET" action="{{ route('companies.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        
                        <div class="space-y-6">
                            <!-- Industry -->
                            @if($industries->count() > 0)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Industry') }}</h3>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach($industries as $industry)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="industry" value="{{ $industry }}" form="filters" 
                                                       @checked(request('industry') === $industry)
                                                       onchange="document.getElementById('filters').submit()"
                                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500 cursor-pointer">
                                                <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $industry }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Company Size -->
                            @if($sizes->count() > 0)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Company Size') }}</h3>
                                    <div class="space-y-2">
                                        @foreach($sizes as $size)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="size" value="{{ $size }}" form="filters" 
                                                       @checked(request('size') === $size)
                                                       onchange="document.getElementById('filters').submit()"
                                                       class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500 cursor-pointer">
                                                <span class="text-sm text-gray-700 group-hover:text-violet-600">{{ $size }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Browse Jobs Card -->
                <div class="bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-2xl border border-violet-100 p-6">
                    <div class="text-center">
                        <i class="fa-solid fa-briefcase text-3xl text-violet-600 mb-3"></i>
                        <p class="text-sm font-bold text-gray-900 mb-1">{{ __('Looking for jobs?') }}</p>
                        <p class="text-xs text-gray-600 mb-4">{{ __('Browse thousands of job opportunities.') }}</p>
                        <a href="{{ route('jobs.index', ['list' => '1']) }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors">
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
                        <p class="text-lg font-semibold text-gray-900">
                            <span class="text-violet-600">{{ number_format($companies->total()) }}</span> 
                            <span class="text-gray-700">{{ __('companies found') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Company Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($companies as $company)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-violet-300 transition-all duration-200 overflow-hidden group">
                            <div class="p-6">
                                <!-- Company Logo & Info -->
                                <div class="flex items-start gap-4 mb-4">
                                    @if($company->logo_path)
                                        <img class="w-16 h-16 rounded-xl object-cover border border-gray-200 flex-shrink-0" 
                                             src="{{ Storage::url($company->logo_path) }}" 
                                             alt="{{ $company->name }} logo">
                                    @else
                                        <div class="w-16 h-16 rounded-xl border border-gray-200 bg-gradient-to-br from-violet-50 to-fuchsia-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-building text-2xl text-violet-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('companies.show', $company) }}" class="block group-hover:text-violet-600 transition-colors">
                                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $company->name }}</h3>
                                        </a>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $company->location?->city ?? ($company->location?->country ?? __('Location not specified')) }}</p>
                                        @if($company->industry)
                                            <span class="inline-block mt-2 px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-medium">
                                                {{ $company->industry }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Company Description -->
                                @if($company->description)
                                    <p class="text-sm text-gray-600 mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ str($company->description)->limit(100) }}
                                    </p>
                                @endif

                                <!-- Company Info -->
                                <div class="flex flex-wrap items-center gap-3 mb-4 text-xs text-gray-500">
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
                                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                    <a href="{{ route('companies.show', $company) }}" 
                                       class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-4 py-2.5 text-sm text-center transition-colors">
                                        {{ __('View Company') }}
                                    </a>
                                    @if($company->website_url)
                                        <a href="{{ $company->website_url }}" target="_blank" rel="noopener" 
                                           class="px-4 py-2.5 border border-gray-300 hover:border-violet-600 text-gray-700 hover:text-violet-600 font-semibold rounded-lg text-sm bg-white transition-colors">
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
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <i class="fa-solid fa-building text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No companies found') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('Try adjusting your filters or search terms') }}</p>
                        <a href="{{ route('companies.index') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>

