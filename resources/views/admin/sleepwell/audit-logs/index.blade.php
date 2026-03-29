@section('meta_title', __('SleepWell Audit Logs - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">{{ __('SleepWell Audit Logs') }}</h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
        </div>

        <div class="surface-card mb-4 p-4">
            <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <input name="entity_type" value="{{ $entityType }}" placeholder="Entity type" class="rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                <input name="action" value="{{ $action }}" placeholder="Action (create/update/delete)" class="rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                <button class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Filter') }}</button>
            </form>
        </div>

        <div class="surface-card overflow-x-auto p-4">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead>
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="px-3 py-2">{{ __('When') }}</th>
                        <th class="px-3 py-2">{{ __('Actor') }}</th>
                        <th class="px-3 py-2">{{ __('Entity') }}</th>
                        <th class="px-3 py-2">{{ __('Action') }}</th>
                        <th class="px-3 py-2">{{ __('IP') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-3 py-3">{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                            <td class="px-3 py-3">{{ $log->actor?->name ?? 'system' }}</td>
                            <td class="px-3 py-3">{{ class_basename($log->entity_type) }}#{{ $log->entity_id }}</td>
                            <td class="px-3 py-3">{{ $log->action }}</td>
                            <td class="px-3 py-3">{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</x-app-layout>
