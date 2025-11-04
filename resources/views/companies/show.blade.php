<x-app-layout>
    <div class="bg-indigo-900 text-white py-10">
        <div class="max-w-5xl mx-auto px-4">
            <h1 class="text-3xl font-bold">{{ $company->name }}</h1>
            <p class="text-indigo-200">{{ $company->website_url }}</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <aside class="md:col-span-4">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <img class="w-24 h-24 rounded-lg object-cover ring-1 ring-gray-200" src="{{ $company->logo_path ? Storage::url($company->logo_path) : 'https://placehold.co/128x128' }}" alt="{{ $company->name }} logo">
                <div class="mt-4 text-sm text-gray-600">Industry</div>
                <div class="font-semibold text-gray-900">{{ $company->industry ?: '-' }}</div>
                <div class="mt-4 text-sm text-gray-600">Location</div>
                <div class="font-semibold text-gray-900">{{ $company->location?->city }}</div>
                <div class="mt-4 text-sm text-gray-600">About</div>
                <div class="text-gray-800">{!! nl2br(e($company->description)) !!}</div>
            </div>
        </aside>
        <main class="md:col-span-8 space-y-4">
            <h2 class="text-xl font-semibold">Open Positions</h2>
            @forelse($company->jobs as $job)
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <a class="text-lg font-semibold text-gray-900 hover:text-indigo-700" href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                    <div class="text-sm text-gray-600">{{ $job->location?->city ?? 'Remote' }} • {{ str($job->employment_type)->replace('_',' ')->title() }}</div>
                </div>
            @empty
                <p class="text-gray-600">No open positions at the moment.</p>
            @endforelse
        </main>
    </div>
</x-app-layout>


