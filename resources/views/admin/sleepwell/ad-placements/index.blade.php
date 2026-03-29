@section('meta_title', __('SleepWell Ad Placements - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">{{ __('SleepWell Ad Placements') }}</h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
            <a href="{{ route('admin.sleepwell.ad-placements.create') }}"
               class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                {{ __('Add Ad Placement') }}
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="surface-card overflow-x-auto p-4">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead>
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="px-3 py-2">{{ __('Screen') }}</th>
                        <th class="px-3 py-2">{{ __('Slot') }}</th>
                        <th class="px-3 py-2">{{ __('Format') }}</th>
                        <th class="px-3 py-2">{{ __('Enabled') }}</th>
                        <th class="px-3 py-2">{{ __('Priority') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($placements as $placement)
                        <tr>
                            <td class="px-3 py-3">{{ $placement->screen }}</td>
                            <td class="px-3 py-3">{{ $placement->slot_key }}</td>
                            <td class="px-3 py-3">{{ $placement->format }}</td>
                            <td class="px-3 py-3">{{ $placement->enabled ? 'Yes' : 'No' }}</td>
                            <td class="px-3 py-3">{{ $placement->priority }}</td>
                            <td class="px-3 py-3 text-right">
                                <a href="{{ route('admin.sleepwell.ad-placements.edit', $placement) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.sleepwell.ad-placements.destroy', $placement) }}" class="inline-block ml-3" onsubmit="return confirm('Delete this placement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $placements->links() }}</div>
    </div>
</x-app-layout>
