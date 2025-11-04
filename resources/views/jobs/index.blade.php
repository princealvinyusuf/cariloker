@php
    $employmentTypes = [
        'full_time' => 'Full-Time',
        'part_time' => 'Part-Time',
        'contract' => 'Contract',
        'internship' => 'Internship',
        'freelance' => 'Freelance',
    ];
@endphp

<x-app-layout>
    <div class="bg-indigo-900 text-white py-10">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold mb-6">Find your dream job</h1>
            <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <label class="sr-only" for="q">Search</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Search job title, keywords or company" class="w-full rounded-lg border-0 px-4 py-3 text-gray-900" />
                </div>
                <div class="md:col-span-4">
                    <label class="sr-only" for="location">Location</label>
                    <input id="location" name="location" value="{{ request('location') }}" placeholder="Location (e.g. Jakarta)" class="w-full rounded-lg border-0 px-4 py-3 text-gray-900" />
                </div>
                <div class="md:col-span-2">
                    <select name="type" class="w-full rounded-lg border-0 px-4 py-3 text-gray-900">
                        <option value="">Any Type</option>
                        @foreach($employmentTypes as $key => $label)
                            <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg px-4 py-3">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <aside class="md:col-span-3 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-700">Filters</h2>
                    <a href="{{ route('jobs.index') }}" class="text-xs text-indigo-600">Clear All</a>
                </div>
                <div class="space-y-4 bg-white rounded-xl p-4 shadow-sm">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-2">Job Type</p>
                        <div class="space-y-2">
                            @foreach($employmentTypes as $key => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="type" value="{{ $key }}" form="filters" @checked(request('type')===$key)
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Min. Salary (IDR)</label>
                        <input type="number" name="min_salary" value="{{ request('min_salary') }}" form="filters" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Experience (years)</label>
                        <input type="number" name="experience" value="{{ request('experience') }}" form="filters" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remote" value="1" form="filters" @checked(request('remote')) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Remote only</span>
                    </div>
                    <form id="filters" method="GET" action="{{ route('jobs.index') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                    </form>
                </div>
            </div>

            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <p class="text-sm font-semibold text-indigo-900">Upload your resume</p>
                <p class="text-xs text-indigo-800 mt-1">We'll match you with the best jobs.</p>
                <a href="{{ route('register') }}" class="inline-flex mt-3 px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm">Get started</a>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">Categories</p>
                <div class="mt-3 space-y-2">
                    @foreach($categories as $category)
                        <a class="block text-sm text-gray-700 hover:text-indigo-600" href="{{ route('jobs.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="md:col-span-6 space-y-4">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <p>{{ number_format($jobs->total()) }} results found</p>
                <form>
                    <select name="sort" class="rounded-lg border-gray-300" onchange="this.form.submit()">
                        <option value="date" @selected(request('sort')==='date')>Date Posted</option>
                        <option value="salary" @selected(request('sort')==='salary')>Salary</option>
                    </select>
                    @foreach(request()->except(['sort','page']) as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                </form>
            </div>

            @foreach($jobs as $job)
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-start gap-4">
                        <img class="w-12 h-12 rounded-lg object-cover ring-1 ring-gray-200" src="{{ $job->company->logo_path ? Storage::url($job->company->logo_path) : 'https://placehold.co/80x80' }}" alt="{{ $job->company->name }} logo">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-lg font-semibold text-gray-900 hover:text-indigo-700">{{ $job->title }}</a>
                                    <div class="text-sm text-gray-600">{{ $job->company->name }} • {{ $job->location?->city ?? 'Remote' }}</div>
                                </div>
                                @auth
                                    <form method="POST" action="{{ route('jobs.save', $job) }}">
                                        @csrf
                                        <button class="text-sm px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">Save Job</button>
                                    </form>
                                @endauth
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                <span class="px-2 py-1 rounded-full bg-gray-100">{{ $employmentTypes[$job->employment_type] ?? $job->employment_type }}</span>
                                @if($job->salary_min)
                                    <span class="px-2 py-1 rounded-full bg-gray-100">{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} {{ $job->salary_currency }}</span>
                                @endif
                                @if($job->is_remote)
                                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">Remote</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div>
                {{ $jobs->links() }}
            </div>
        </main>

        <aside class="md:col-span-3 space-y-6">
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">Be the first to see new jobs in <span class="text-indigo-600">{{ request('location') ?: 'your city' }}</span></p>
                <form class="mt-3 flex gap-2">
                    <input type="email" placeholder="Email" class="flex-1 rounded-lg border-gray-300" />
                    <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Subscribe</button>
                </form>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">Popular Companies</p>
                <ul class="mt-3 space-y-3">
                    @foreach($popularCompanies as $company)
                        <li class="flex items-center justify-between text-sm">
                            <a href="{{ route('companies.show', $company) }}" class="text-gray-700 hover:text-indigo-600">{{ $company->name }}</a>
                            <span class="text-gray-500">{{ $company->jobs_count }} jobs</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</x-app-layout>


