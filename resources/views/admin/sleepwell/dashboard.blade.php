@section('meta_title', __('SleepWell Dashboard - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('SleepWell Admin') }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Main Dashboard') }}
            </a>
            <a href="{{ route('admin.sleepwell.tracks.index') }}"
               class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                {{ __('Manage SleepWell Tracks') }}
            </a>
            <a href="{{ route('admin.sleepwell.onboarding.index') }}"
               class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600">
                {{ __('Manage Onboarding UI') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Total listeners') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalListeners) }}</p>
            </div>
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Active today') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($activeToday) }}</p>
            </div>
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Completed sessions') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($completedSessions) }}</p>
            </div>
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Avg duration (min)') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($avgDurationMinutes) }}</p>
            </div>
        </div>

        <div class="surface-card mt-6 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('SleepWell API Endpoints') }}</h3>
            <ul class="mt-4 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                <li><code>GET /api/v1/sleepwell/onboarding/content</code></li>
                <li><code>POST /api/v1/sleepwell/onboarding</code></li>
                <li><code>POST /api/v1/sleepwell/onboarding/responses</code></li>
                <li><code>GET /api/v1/sleepwell/catalog</code></li>
                <li><code>POST /api/v1/sleepwell/sessions/start</code></li>
                <li><code>POST /api/v1/sleepwell/sessions/{session}/event</code></li>
                <li><code>POST /api/v1/sleepwell/sessions/{session}/end</code></li>
                <li><code>POST /api/v1/sleepwell/sleep-now</code></li>
                <li><code>GET /api/v1/sleepwell/insights/{deviceId}</code></li>
                <li><code>GET|POST /api/v1/sleepwell/mix-presets</code></li>
            </ul>
        </div>
    </div>
</x-app-layout>
