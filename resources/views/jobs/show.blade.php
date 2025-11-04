<x-app-layout>
    <div class="bg-indigo-900 text-white py-10">
        <div class="max-w-5xl mx-auto px-4">
            <a href="{{ route('jobs.index') }}" class="text-indigo-200 text-sm">← {{ __('Back to search') }}</a>
            <h1 class="text-3xl font-bold mt-2">{{ $job->title }}</h1>
            <p class="text-indigo-200">{{ $job->company->name }} • {{ $job->location?->city ?? __('Remote') }}</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <main class="md:col-span-8">
            <div class="bg-white rounded-xl p-6 shadow-sm space-y-6">
                <div class="flex items-center gap-4">
                    <img class="w-16 h-16 rounded-lg object-cover ring-1 ring-gray-200" src="{{ $job->company->logo_path ? Storage::url($job->company->logo_path) : 'https://placehold.co/96x96' }}" alt="{{ $job->company->name }} logo">
                    <div>
                        <div class="text-gray-900 font-semibold">{{ $job->company->name }}</div>
                        <div class="text-gray-600 text-sm">{{ $job->location?->city ?? 'Remote' }}</div>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">{{ __('Job Description') }}</h2>
                    <div class="prose max-w-none mt-3">{!! nl2br(e($job->description)) !!}</div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">{{ __('Skills') }}</h2>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
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

                    <div class="text-gray-600">{{ __('URL') }}</div>
                    <div class="font-medium text-gray-900">
                        @if($job->external_url)
                            <a href="{{ $job->external_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ __('Open link') }}</a>
                        @else
                            -
                        @endif
                    </div>

                    <div class="text-gray-600">{{ __('Gender') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->gender ? __((string) str($job->gender)->title()) : '-' }}</div>

                    <div class="text-gray-600">{{ __('Work Arrangement') }}</div>
                    <div class="font-medium text-gray-900">{{ $job->work_arrangement ? __((string) str($job->work_arrangement)->title()) : ($job->is_remote ? __('Remote') : '-') }}</div>

                    <div class="text-gray-600">{{ __('Job Type') }}</div>
                    <div class="font-medium text-gray-900">{{ __(str($job->employment_type)->replace('_',' ')->title()) }}</div>

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

                @auth
                    <form method="POST" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700">{{ __('Resume (PDF/DOC, max 5MB)') }}</label>
                        <input type="file" name="resume" class="w-full border-gray-300 rounded-lg" />
                        <label class="block text-sm font-medium text-gray-700">{{ __('Cover Letter') }}</label>
                        <textarea name="cover_letter" rows="4" class="w-full border-gray-300 rounded-lg"></textarea>
                        <button class="w-full mt-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg px-4 py-3">{{ __('Apply Now') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-3 block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg px-4 py-3">{{ __('Sign in to Apply') }}</a>
                @endauth
            </div>
        </aside>
    </div>
</x-app-layout>


