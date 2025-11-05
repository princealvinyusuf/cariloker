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
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-10 border-b border-violet-100">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold mb-6 text-gray-900">{{ __('Find your dream job') }}</h1>
            <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <label class="sr-only" for="q">{{ __('Search') }}</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search job title, keywords or company') }}" class="w-full rounded-xl border border-violet-200 bg-white px-4 py-3 text-gray-900" />
                </div>
                <div class="md:col-span-4">
                    <label class="sr-only" for="location">{{ __('Location') }}</label>
                    <input id="location" name="location" value="{{ request('location') }}" placeholder="{{ __('Location (e.g. Jakarta)') }}" class="w-full rounded-xl border border-violet-200 bg-white px-4 py-3 text-gray-900" />
                </div>
                <div class="md:col-span-2">
                    <select name="type" class="w-full rounded-xl border border-violet-200 bg-white px-4 py-3 text-gray-900">
                        <option value="">{{ __('Any Type') }}</option>
                        @foreach($employmentTypes as $key => $label)
                            <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl px-4 py-3">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <aside class="md:col-span-3 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-700">{{ __('Filter Jobs') }}</h2>
                    <a href="{{ route('jobs.index') }}" class="text-xs text-violet-700">{{ __('Clear All') }}</a>
                </div>
                <div class="space-y-5 bg-white rounded-2xl p-4 shadow-sm ring-1 ring-violet-100">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-2">{{ __('Job Type') }}</p>
                        <div class="space-y-2">
                            @foreach($employmentTypes as $key => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="type" value="{{ $key }}" form="filters" @checked(request('type')===$key)
                                           class="rounded border-violet-300 text-violet-600 focus:ring-violet-600">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Min. Salary (IDR)') }}</label>
                        <input type="number" name="min_salary" value="{{ request('min_salary') }}" form="filters" class="mt-1 w-full rounded-xl border-violet-200">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">{{ __('Experience (years)') }}</label>
                        <input type="number" name="experience" value="{{ request('experience') }}" form="filters" class="mt-1 w-full rounded-xl border-violet-200">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remote" value="1" form="filters" @checked(request('remote')) class="rounded border-violet-300 text-violet-600 focus:ring-violet-600">
                        <span class="text-sm text-gray-700">{{ __('Remote only') }}</span>
                    </div>
                    <form id="filters" method="GET" action="{{ route('jobs.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                    </form>
                </div>
            </div>

            <div class="bg-violet-50 p-4 rounded-2xl border border-violet-100">
                <p class="text-sm font-semibold text-violet-800">{{ __('Upload your resume') }}</p>
                <p class="text-xs text-violet-700 mt-1">{{ __('We\'ll match you with the best jobs.') }}</p>
                <a href="{{ route('register') }}" class="inline-flex mt-3 px-3 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm">{{ __('Get started') }}</a>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm ring-1 ring-violet-100">
                <p class="text-sm font-semibold text-gray-800">{{ __('Categories') }}</p>
                <div class="mt-3 space-y-2">
                    @foreach($categories as $category)
                        <a class="block text-sm text-gray-700 hover:text-violet-700" href="{{ route('jobs.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
        <main class="md:col-span-9">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                <p>{{ number_format($jobs->total()) }} {{ __('results found') }}</p>
                <form>
                    <select name="sort" class="rounded-xl border-violet-200" onchange="this.form.submit()">
                        <option value="date" @selected(request('sort')==='date')>{{ __('Date Posted') }}</option>
                        <option value="salary" @selected(request('sort')==='salary')>{{ __('Salary') }}</option>
                    </select>
                    @foreach(request()->except(['sort','page']) as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobs as $job)
                    <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-violet-100">
                        <div class="flex items-start gap-3">
                            @if($job->company->logo_path)
                                <img class="w-10 h-10 rounded-lg object-cover ring-1 ring-gray-200" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo">
                            @else
                                <div class="w-10 h-10 rounded-lg ring-1 ring-gray-200 bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-building text-lg"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="text-xs text-gray-500">{{ $job->company->name }}</div>
                                <a href="{{ route('jobs.show', $job) }}" class="block text-base font-semibold text-gray-900 hover:text-violet-700">{{ $job->title }}</a>
                                <div class="mt-1 text-xs text-gray-500">{{ $job->location?->city ?? 'Remote' }}</div>
                                <p class="mt-2 text-sm text-gray-600">{{ str($job->description)->limit(120) }}</p>
                                <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                    @if($job->openings)
                                        <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700">{{ $job->openings }} {{ __('Positions') }}</span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">{{ $employmentTypes[$job->employment_type] ?? $job->employment_type }}</span>
                                    @if(!is_null($job->experience_min) || !is_null($job->experience_max))
                                        <span class="px-2 py-0.5 rounded-full bg-violet-50 text-violet-700">{{ $job->experience_min ?? 0 }}–{{ $job->experience_max ?? '∞' }} {{ __('Years') }}</span>
                                    @endif
                                    @if($job->salary_min)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">{{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - '.number_format($job->salary_max) : '' }} {{ $job->salary_currency }}</span>
                                    @endif
                                    @if($job->work_arrangement || $job->is_remote)
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700">{{ $job->work_arrangement === 'onsite' ? 'WFO' : ($job->work_arrangement === 'remote' || $job->is_remote ? 'WFH' : 'Hybrid') }}</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    @if($job->external_url)
                                        <a href="{{ $job->external_url }}" target="_blank" rel="noopener" class="px-3 py-2 rounded-lg bg-violet-600 hover:bg-violet-700 text-white text-sm">{{ __('Apply Now') }}</a>
                                    @else
                                        <a href="{{ route('jobs.show', $job) }}" class="px-3 py-2 rounded-lg bg-violet-600 hover:bg-violet-700 text-white text-sm">{{ __('Apply Now') }}</a>
                                    @endif
                                    <a href="{{ route('jobs.show', $job) }}" class="px-3 py-2 rounded-lg border border-violet-200 text-gray-900 text-sm">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        </main>
    </div>
</x-app-layout>


