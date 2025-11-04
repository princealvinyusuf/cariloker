<x-app-layout>
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-5xl mx-auto px-4 py-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <a href="{{ route('jobs.index') }}" class="text-primary text-sm">← {{ __('Back to search') }}</a>
                    <h1 class="text-3xl font-bold mt-1 text-gray-900 dark:text-gray-100">{{ $job->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-300">{{ $job->company->name }} • {{ $job->location?->city ?? __('Remote') }}</p>
                </div>
                @if($job->external_url)
                    <a href="{{ $job->external_url }}" target="_blank" rel="noopener" class="hidden md:inline-flex items-center px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-white font-semibold">{{ __('Klik Untuk Melamar') }}</a>
                @endif
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                @if($job->employment_type)
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</span>
                @endif
                @if($job->work_arrangement || $job->is_remote)
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : __('Remote') }}</span>
                @endif
                @if($job->education_level)
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">{{ $job->education_level }}</span>
                @endif
                @if($job->experience_min || $job->experience_max)
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">{{ __('Experience') }} {{ $job->experience_min }} - {{ $job->experience_max }} {{ __('years') }}</span>
                @endif
                @if($job->salary_min)
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">{{ number_format($job->salary_min) }}{{ $job->salary_max ? ' - '.number_format($job->salary_max) : '' }} {{ $job->salary_currency }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <main class="md:col-span-8 space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    @if($job->company->logo_path)
                        <img class="w-16 h-16 rounded-lg object-cover ring-1 ring-gray-200" src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }} logo">
                    @else
                        <div class="w-16 h-16 rounded-lg ring-1 ring-gray-200 bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="text-gray-900 font-semibold">{{ $job->company->name }}</div>
                        <div class="text-gray-600 text-sm">{{ $job->location?->city ?? __('Remote') }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold">{{ __('Requirements') }}</h2>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @if($job->work_arrangement || $job->is_remote)
                        <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : __('Remote') }}</span>
                    @endif
                    @if($job->experience_min || $job->experience_max)
                        <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">{{ $job->experience_min }} - {{ $job->experience_max }} {{ __('years experience') }}</span>
                    @endif
                    @if($job->education_level)
                        <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">{{ $job->education_level }}</span>
                    @endif
                    @if($job->openings)
                        <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">{{ __('Openings (people)') }}: {{ $job->openings }}</span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold">{{ __('Skills') }}</h2>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($job->skills as $skill)
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold">{{ __('Job Description') }}</h2>
                <div class="prose max-w-none mt-3">{!! nl2br(e($job->description)) !!}</div>
            </div>
        </main>
        <aside class="md:col-span-4">
            <div class="bg-white rounded-xl p-6 shadow-sm sticky top-6 space-y-3">
                <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                    <div class="text-gray-600">{{ __('Company') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->company?->name }}</div>

                    <div class="text-gray-600">{{ __('Province') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->location?->state }}</div>

                    <div class="text-gray-600">{{ __('City/Regency') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->location?->city }}</div>

                    <div class="text-gray-600">{{ __('Sector') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->company?->industry ?? '-' }}</div>

                    <div class="text-gray-600">{{ __('Openings (people)') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->openings ?? '-' }}</div>

                    <div class="text-gray-600">{{ __('Date Posted') }}</div>
                    <div class="font-medium text-gray-900">{{ optional($job->posted_at ?? $job->created_at)->format('d M Y') }}</div>

                    <div class="text-gray-600">{{ __('Valid Until') }}</div>
                    <div class="font-medium text-gray-900">{{ optional($job->valid_until)->format('d M Y') ?? '-' }}</div>

                    

                    <div class="text-gray-600">{{ __('Gender') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->gender ? __((string) str($job->gender)->title()) : '-' }}</div>

                    <div class="text-gray-600">{{ __('Work Arrangement') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : ($job->is_remote ? __('Remote') : '-') }}</div>

                    <div class="text-gray-600">{{ __('Job Type') }}</div>
                    <div class="font-medium text-gray-900">{{ __((string) str($job->employment_type)->replace('_',' ')->title()) }}</div>

                    <div class="text-gray-600">{{ __('Job Level') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->seniority_level ? __((string) str($job->seniority_level)->title()) : '-' }}</div>

                    <div class="text-gray-600">{{ __('Education') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->education_level ?? '-' }}</div>

                    <div class="text-gray-600">{{ __('Salary') }}</div>
                    <div class="font-medium text-gray-900">
                        @if($job->salary_min)
                            {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} {{ $job->salary_currency }}
                        @else
                            -
                        @endif
                    </div>

                    <div class="text-gray-600">{{ __('Job Category') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->category?->name ?? '-' }}</div>
                </div>

                @if($job->external_url)
                    <a href="{{ $job->external_url }}" target="_blank" rel="noopener" class="md:hidden mt-3 block w-full text-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-4 py-3">{{ __('Klik Untuk Melamar') }}</a>
                @else
                    @auth
                        <form method="POST" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700">{{ __('Resume (PDF/DOC, max 5MB)') }}</label>
                            <input type="file" name="resume" class="w-full border-gray-300 rounded-lg" />
                            <label class="block text-sm font-medium text-gray-700">{{ __('Cover Letter') }}</label>
                            <textarea name="cover_letter" rows="4" class="w-full border-gray-300 rounded-lg"></textarea>
                            <button class="w-full mt-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-4 py-3">{{ __('Apply Now') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mt-3 block w-full text-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-4 py-3">{{ __('Sign in to Apply') }}</a>
                    @endauth
                @endif
            </div>
        </aside>
    </div>
</x-app-layout>


