<x-app-layout>
    <div class="bg-primary-dark text-white py-10">
        <div class="max-w-5xl mx-auto px-4">
            <h1 class="text-3xl font-bold">{{ $company->name }}</h1>
            <p class="text-primary-light">{{ $company->website_url }}</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        <aside class="md:col-span-4">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                @if($company->logo_path)
                    <img class="w-24 h-24 rounded-lg object-cover ring-1 ring-gray-200" src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }} logo" loading="lazy">
                @else
                    <div class="w-24 h-24 rounded-lg ring-1 ring-gray-200 bg-gray-100 flex items-center justify-center text-gray-500">
                        <i class="fa-solid fa-building text-3xl"></i>
                    </div>
                @endif
                <div class="mt-4 text-sm text-gray-600">{{ __('Industry') }}</div>
                <div class="font-semibold text-gray-900">{{ $company->industry ?: '-' }}</div>
                <div class="mt-4 text-sm text-gray-600">{{ __('Location') }}</div>
                <div class="font-semibold text-gray-900">{{ $company->location?->city }}</div>
                <div class="mt-4 text-sm text-gray-600">{{ __('About') }}</div>
                <div class="text-gray-800">{!! nl2br(e($company->description)) !!}</div>
            </div>
        </aside>
        <main class="md:col-span-8 space-y-4">
            <h2 class="text-xl font-semibold">{{ __('Open Positions') }}</h2>
            @forelse($company->jobs as $job)
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <a class="text-lg font-semibold text-gray-900 hover:text-primary-dark" href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                <div class="text-sm text-gray-600">{{ $job->location?->city ?? __('Remote') }} • {{ str($job->employment_type)->replace('_',' ')->title() }}</div>
                </div>
            @empty
                <p class="text-gray-600">{{ __('No open positions at the moment.') }}</p>
            @endforelse
        </main>
    </div>
</x-app-layout>


