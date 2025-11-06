<x-app-layout>
    <!-- Header / Hero -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white border-b border-violet-100">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <a href="{{ route('jobs.index') }}" class="text-violet-600 text-sm">← {{ __('Back to search') }}</a>
                    <h1 class="text-3xl font-bold mt-1 text-gray-900">{{ $job->title }}</h1>
                    <p class="text-gray-600">{{ $job->company->name }} • {{ $job->location?->city ?? __('Remote') }}</p>
                </div>
                @if($job->external_url)
                    <a href="{{ $job->external_url }}" target="_blank" rel="noopener" class="hidden md:inline-flex items-center px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold shadow-sm">{{ __('Apply Now') }}</a>
                @endif
            </div>

            <!-- Chips -->
            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                @if($job->openings)
                    <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-700 font-medium">{{ $job->openings }} {{ __('Positions') }}</span>
                @endif
                @if($job->employment_type)
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</span>
                @endif
                @if($job->salary_min)
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-medium">{{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - '.number_format($job->salary_max) : '' }} {{ $job->salary_currency }}</span>
                @endif
                @if($job->work_arrangement || $job->is_remote)
                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-medium">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->upper()) : __('Remote') }}</span>
                @endif
                @if($job->experience_min || $job->experience_max)
                    <span class="px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 font-medium">{{ $job->experience_min ?? 0 }}–{{ $job->experience_max ?? '∞' }} {{ __('Years') }}</span>
                @endif
            </div>

            <!-- Tabs (visual only) -->
            <div class="mt-6 flex items-center gap-6 text-sm">
                <a class="text-violet-700 font-semibold" href="#description">{{ __('Job Description') }}</a>
                @if($job->education_level)
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-700">{{ __('Education') }}: <span class="font-medium">{{ $job->education_level }}</span></span>
                @endif
                <span class="text-gray-400">•</span>
                <span class="text-gray-700">{{ __('Total Applicants') }}: <span class="font-medium">{{ $totalApplicants ?? 0 }}</span></span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-700">{{ __('Views') }}: <span class="font-medium">{{ number_format($job->views) }}</span></span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Main -->
        <main class="md:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-violet-100" id="description">
                <div class="flex items-center gap-4">
                    @if($job->company->logo_path)
                        <img class="w-16 h-16 rounded-xl object-cover ring-1 ring-gray-200" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo">
                    @else
                        <div class="w-16 h-16 rounded-xl ring-1 ring-gray-200 bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="text-gray-900 font-semibold">{{ $job->company->name }}</div>
                        <div class="text-gray-600 text-sm">{{ $job->location?->city ?? __('Remote') }}</div>
                    </div>
                </div>
                <div class="mt-6 prose max-w-none">{!! nl2br(e($job->description)) !!}</div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-violet-100">
                <h2 class="text-lg font-semibold">{{ __('Requirements') }}</h2>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @if($job->work_arrangement || $job->is_remote)
                        <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : __('Remote') }}</span>
                    @endif
                    @if($job->experience_min || $job->experience_max)
                        <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700">{{ $job->experience_min }} - {{ $job->experience_max }} {{ __('years experience') }}</span>
                    @endif
                    @if($job->education_level)
                        <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700">{{ $job->education_level }}</span>
                    @endif
                    @if($job->openings)
                        <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700">{{ __('Openings (people)') }}: {{ $job->openings }}</span>
                    @endif
                </div>
            </div>

            @if($job->skills->count())
                <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-violet-100">
                    <h2 class="text-lg font-semibold">{{ __('Skills') }}</h2>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                            <span class="px-2 py-1 text-xs rounded-full bg-violet-50 text-violet-700">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>

        <!-- Sidebar -->
        <aside class="md:col-span-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-violet-100 sticky top-6 space-y-6">
                @if(isset($job->reviews) && $job->reviews->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('Review') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        @foreach($job->reviews as $review)
                            <li class="flex items-center gap-2 {{ $review->type === 'positive' ? 'text-emerald-700' : 'text-rose-600' }}">
                                <i class="fa-solid {{ $review->type === 'positive' ? 'fa-thumbs-up' : 'fa-thumbs-down' }}"></i>
                                <span>{{ $review->text }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($job->benefits) && $job->benefits->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('Benefits & Perks') }}</h3>
                    <div class="mt-3 grid grid-cols-3 gap-3 text-center">
                        @foreach($job->benefits as $benefit)
                            <div class="rounded-xl border border-violet-100 p-3">
                                <i class="{{ $benefit->icon }} text-violet-600"></i>
                                <div class="text-xs mt-1">{{ $benefit->name }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                    <div class="text-gray-600">{{ __('Company') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->company?->name }}</div>

                    <div class="text-gray-600">{{ __('Province') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->location?->state }}</div>

                    <div class="text-gray-600">{{ __('City/Regency') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->location?->city }}</div>

                    <div class="text-gray-600">{{ __('Date Posted') }}</div>
                    <div class="font-medium text-gray-900">{{ optional($job->posted_at ?? $job->created_at)->format('d M Y') }}</div>

                    <div class="text-gray-600">{{ __('Valid Until') }}</div>
                    <div class="font-medium text-gray-900">{{ optional($job->valid_until)->format('d M Y') ?? '-' }}</div>

                    <div class="text-gray-600">{{ __('Job Type') }}</div>
                    <div class="font-medium text-gray-900">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</div>

                    <div class="text-gray-600">{{ __('Education') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->education_level ?? '-' }}</div>

                    <div class="text-gray-600">{{ __('Salary') }}</div>
                    <div class="font-medium text-gray-900">
                        @if($job->salary_min)
                            {{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - '.number_format($job->salary_max) : '' }} {{ $job->salary_currency }}
                        @else
                            -
                        @endif
                    </div>

                    <div class="text-gray-600">{{ __('Views') }}</div>
                    <div class="font-medium text-gray-900">{{ number_format($job->views) }}</div>
                </div>

                @if($job->external_url)
                    <a href="{{ $job->external_url }}" target="_blank" rel="noopener" class="md:hidden block w-full text-center bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl px-4 py-3">{{ __('Apply Now') }}</a>
                @endif
            </div>
        </aside>
    </div>

    @if(isset($relatedJobs) && $relatedJobs->count())
        <div class="max-w-6xl mx-auto px-4 pb-12">
            <h2 class="text-xl font-semibold mb-4">{{ __('More like this') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedJobs as $related)
                    <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-violet-100">
                        <div class="flex items-start gap-4">
                            @if($related->company->logo_path)
                                <img class="w-12 h-12 rounded-lg object-cover ring-1 ring-gray-200" src="{{ Storage::url($related->company->logo_path) }}" alt="{{ $related->company->name }} logo">
                            @else
                                <div class="w-12 h-12 rounded-lg ring-1 ring-gray-200 bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-building text-xl"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('jobs.show', $related) }}" class="text-base font-semibold text-gray-900 hover:text-violet-700">{{ $related->title }}</a>
                                        <div class="text-xs text-gray-600">{{ $related->company->name }} • {{ $related->location?->city ?? 'Remote' }}</div>
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px] text-gray-600">
                                    @if($related->employment_type)
                                        <span class="px-2 py-0.5 rounded-full bg-violet-50 text-violet-700">{{ str($related->employment_type)->replace('_',' ')->title() }}</span>
                                    @endif
                                    @if($related->salary_min)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">{{ number_format($related->salary_min) }}{{ $related->salary_max ? ' - '.number_format($related->salary_max) : '' }} {{ $related->salary_currency }}</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <a href="{{ route('jobs.show', $related) }}" class="w-full px-3 py-2 rounded-lg bg-violet-600 hover:bg-violet-700 text-white text-sm text-center transition-colors">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Floating Apply Button -->
    @if($job->external_url)
        <div class="fixed bottom-6 right-6 z-50">
            <a href="{{ $job->external_url }}" target="_blank" rel="noopener" 
               class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-full px-6 py-4 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <i class="fa-solid fa-paper-plane"></i>
                <span>{{ __('Apply Now') }}</span>
            </a>
        </div>
    @endif
</x-app-layout>
