@section('meta_title', __('SleepWell Home Items - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('SleepWell Home Items') }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
            <a href="{{ route('admin.sleepwell.home-items.create') }}"
               class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                {{ __('Add Home Item') }}
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
                        <th class="px-3 py-2">{{ __('Order') }}</th>
                        <th class="px-3 py-2">{{ __('Section') }}</th>
                        <th class="px-3 py-2">{{ __('Title') }}</th>
                        <th class="px-3 py-2">{{ __('Subtitle') }}</th>
                        <th class="px-3 py-2">{{ __('Status') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-3 py-3">{{ $item->sort_order }}</td>
                            <td class="px-3 py-3">{{ $item->section?->section_key }}</td>
                            <td class="px-3 py-3 font-semibold">{{ $item->title }}</td>
                            <td class="px-3 py-3">{{ $item->subtitle ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="px-3 py-3 text-right">
                                <a href="{{ route('admin.sleepwell.home-items.edit', $item) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.sleepwell.home-items.destroy', $item) }}" class="inline-block ml-3" onsubmit="return confirm('Delete this item?')">
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
        <div class="mt-4">{{ $items->links() }}</div>
    </div>
</x-app-layout>
