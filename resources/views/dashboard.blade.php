@section('meta_title', __('Dashboard - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="surface-card p-6 md:col-span-1">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Welcome!') }}</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __("You're logged in!") }}</p>
                <div class="mt-5 rounded-xl bg-primary-50 p-4 text-sm text-primary-800 dark:bg-primary-900/30 dark:text-primary-200">
                    {{ __('Use the quick links to manage content and monitor portal growth.') }}
                </div>
            </div>

            <div class="surface-card p-6 md:col-span-2">
                <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">{{ __('Quick Actions') }}</h3>
                <div class="grid gap-2 sm:grid-cols-2">
                    <a href="{{ route('about.edit') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Edit About Page') }} →</a>
                    <a href="{{ route('faq.edit') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Edit FAQ Page') }} →</a>
                    <a href="{{ route('cookie-policy.edit') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Edit Cookie Policy') }} →</a>
                    <a href="{{ route('terms-of-service.edit') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Edit Terms of Service') }} →</a>
                    <a href="{{ route('privacy-policy.edit') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Edit Privacy Policy') }} →</a>
                    <a href="{{ route('admin.blog.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Manage Blog') }} →</a>
                    <a href="{{ route('admin.analytics.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Analytics') }} →</a>
                    <a href="{{ route('admin.jobs.import.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('Distribute Data From Staging Table') }} →</a>
                    @if(\Illuminate\Support\Facades\Route::has('admin.sleepwell.dashboard'))
                        <a href="{{ route('admin.sleepwell.dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300">{{ __('SleepWell Admin') }} →</a>
                    @endif
                    <a href="{{ route('jobs.index', ['list' => '1']) }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-primary-500 dark:hover:text-primary-300 sm:col-span-2">{{ __('Browse Jobs') }} →</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
