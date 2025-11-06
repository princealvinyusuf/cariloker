<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-16 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-rose-50 text-rose-600 shadow ring-1 ring-rose-100">
            <i class="fa-solid fa-face-sad-tear text-5xl"></i>
        </div>
        <h1 class="mt-6 text-2xl md:text-3xl font-bold text-gray-900">{{ __('This job has expired') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('Back to Jobs') }} • <a href="{{ route('jobs.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">{{ __('Find Jobs') }}</a></p>

        @if(isset($relatedJobs) && $relatedJobs->count())
            <div class="mt-10 text-left">
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
                                            @php $idr = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.'); @endphp
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">{{ $idr($related->salary_min) }}{{ $related->salary_max ? ' - '.$idr($related->salary_max) : '' }}</span>
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
    </div>
</x-app-layout>


