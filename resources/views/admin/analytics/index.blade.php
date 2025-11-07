<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ modal: null }" @keydown.escape.window="modal = null">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Active Jobs Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex h-full flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Active Jobs') }}
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($activeJobs) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" @click="modal = 'activeJobs'"
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 transition hover:text-violet-700 focus-visible-outline dark:text-violet-400 dark:hover:text-violet-300">
                            <span>{{ __('View details') }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Inactive Jobs Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex h-full flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Inactive Jobs') }}
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($inactiveJobs) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-red-100 p-3 dark:bg-red-900">
                                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" @click="modal = 'inactiveJobs'"
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 transition hover:text-violet-700 focus-visible-outline dark:text-violet-400 dark:hover:text-violet-300">
                            <span>{{ __('View details') }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Total Categories Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex h-full flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Job Categories') }}
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($totalCategories) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" @click="modal = 'categories'"
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 transition hover:text-violet-700 focus-visible-outline dark:text-violet-400 dark:hover:text-violet-300">
                            <span>{{ __('View details') }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Total Views Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex h-full flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Total Views') }}
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($totalViews) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-violet-100 p-3 dark:bg-violet-900">
                                <svg class="h-8 w-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" @click="modal = 'views'"
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 transition hover:text-violet-700 focus-visible-outline dark:text-violet-400 dark:hover:text-violet-300">
                            <span>{{ __('View details') }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Total Applicants Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex h-full flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Total Applicants') }}
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($totalApplicants) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-amber-100 p-3 dark:bg-amber-900">
                                <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a4 4 0 100-8 4 4 0 000 8zm0 0c-4.418 0-8 2.239-8 5v3h9m7-9a4 4 0 110-8 4 4 0 010 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" @click="modal = 'applicants'"
                            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 transition hover:text-violet-700 focus-visible-outline dark:text-violet-400 dark:hover:text-violet-300">
                            <span>{{ __('View details') }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back to Dashboard Link -->
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center font-medium text-violet-600 transition hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        <div x-cloak x-show="modal !== null" class="fixed inset-0 z-40 bg-gray-900/60" x-transition.opacity @click="modal = null"></div>

        <!-- Active Jobs Modal -->
        <div x-cloak x-show="modal === 'activeJobs'" class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true" x-transition>
            <div class="relative w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-900" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Active Jobs Detail') }}</h3>
                    <button type="button" class="rounded-full p-1 text-gray-500 transition hover:text-gray-700 focus-visible-outline dark:text-gray-400 dark:hover:text-gray-200" @click="modal = null">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[28rem] space-y-4 overflow-y-auto px-6 py-5">
                    @forelse ($activeJobDetails as $job)
                        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $job->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($job->company)->name ?? __('Unknown Company') }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                    <p>{{ __('Views:') }} {{ number_format($job->views ?? 0) }}</p>
                                    <p>{{ __('Apply clicks:') }} {{ number_format($job->apply_clicks ?? 0) }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Posted') }}: {{ optional($job->posted_at)->format('d M Y') ?? __('N/A') }}
                                @if ($job->valid_until)
                                    · {{ __('Valid until') }}: {{ $job->valid_until->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No active jobs available yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Inactive Jobs Modal -->
        <div x-cloak x-show="modal === 'inactiveJobs'" class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true" x-transition>
            <div class="relative w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-900" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Inactive Jobs Detail') }}</h3>
                    <button type="button" class="rounded-full p-1 text-gray-500 transition hover:text-gray-700 focus-visible-outline dark:text-gray-400 dark:hover:text-gray-200" @click="modal = null">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[28rem] space-y-4 overflow-y-auto px-6 py-5">
                    @forelse ($inactiveJobDetails as $job)
                        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $job->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($job->company)->name ?? __('Unknown Company') }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                    <p>{{ __('Status:') }} {{ ucfirst($job->status ?? __('Unknown')) }}</p>
                                    <p>{{ __('Updated:') }} {{ optional($job->updated_at)->format('d M Y') ?? __('N/A') }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Posted') }}: {{ optional($job->posted_at)->format('d M Y') ?? __('N/A') }}
                                @if ($job->valid_until)
                                    · {{ __('Expired at') }}: {{ $job->valid_until->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('There are no inactive jobs at the moment.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Categories Modal -->
        <div x-cloak x-show="modal === 'categories'" class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true" x-transition>
            <div class="relative w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-900" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Job Categories Detail') }}</h3>
                    <button type="button" class="rounded-full p-1 text-gray-500 transition hover:text-gray-700 focus-visible-outline dark:text-gray-400 dark:hover:text-gray-200" @click="modal = null">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[26rem] overflow-y-auto px-6 py-5">
                    <div class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">{{ __('Category') }}</th>
                                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">{{ __('Active Jobs') }}</th>
                                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">{{ __('Inactive Jobs') }}</th>
                                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-700 dark:bg-gray-900">
                                @forelse ($categoryDetails as $category)
                                    @php($total = ($category->active_jobs_count ?? 0) + ($category->inactive_jobs_count ?? 0))
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ number_format($category->active_jobs_count ?? 0) }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ number_format($category->inactive_jobs_count ?? 0) }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-gray-100">{{ number_format($total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No categories have been added yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Views Modal -->
        <div x-cloak x-show="modal === 'views'" class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true" x-transition>
            <div class="relative w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-900" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Top Viewed Jobs') }}</h3>
                    <button type="button" class="rounded-full p-1 text-gray-500 transition hover:text-gray-700 focus-visible-outline dark:text-gray-400 dark:hover:text-gray-200" @click="modal = null">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[28rem] space-y-4 overflow-y-auto px-6 py-5">
                    @forelse ($topViewedJobs as $job)
                        <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $job->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($job->company)->name ?? __('Unknown Company') }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                    <p>{{ __('Views:') }} {{ number_format($job->views ?? 0) }}</p>
                                    <p>{{ __('Apply clicks:') }} {{ number_format($job->apply_clicks ?? 0) }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Posted') }}: {{ optional($job->posted_at)->format('d M Y') ?? __('N/A') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No job views have been recorded yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Applicants Modal -->
        <div x-cloak x-show="modal === 'applicants'" class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog" aria-modal="true" x-transition>
            <div class="relative w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-900" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Applicants Detail') }}</h3>
                    <button type="button" class="rounded-full p-1 text-gray-500 transition hover:text-gray-700 focus-visible-outline dark:text-gray-400 dark:hover:text-gray-200" @click="modal = null">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[28rem] space-y-8 overflow-y-auto px-6 py-5">
                    <section>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Applications') }}</h4>
                        <div class="mt-3 space-y-4">
                            @forelse ($recentApplications as $application)
                                <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ optional($application->job)->title ?? __('Job removed') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ optional($application->user)->name ?? __('Guest applicant') }}
                                                @if(optional($application->user)->email)
                                                    · {{ optional($application->user)->email }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                            <p>{{ __('Status:') }} {{ ucfirst($application->status ?? __('submitted')) }}</p>
                                            <p>{{ __('Applied:') }} {{ optional($application->created_at)->format('d M Y') ?? __('N/A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No on-platform applications yet.') }}</p>
                            @endforelse
                        </div>
                    </section>

                    <section>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Top Apply Clicks') }}</h4>
                        <div class="mt-3 space-y-4">
                            @forelse ($applyClickLeaders as $job)
                                <div class="rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $job->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($job->company)->name ?? __('Unknown Company') }}</p>
                                        </div>
                                        <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                            <p>{{ __('Apply clicks:') }} {{ number_format($job->apply_clicks ?? 0) }}</p>
                                            <p>{{ __('Views:') }} {{ number_format($job->views ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No external apply clicks captured yet.') }}</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

