@section('meta_title', __('SleepWell Content Hub - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('SleepWell Content Hub') }}: {{ $screenLabel }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
            <a href="{{ route('admin.sleepwell.content.sections', ['screen' => $screen]) }}"
               class="rounded-xl bg-indigo-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-600">
                {{ __('Manage Sections') }}
            </a>
            <a href="{{ route('admin.sleepwell.content.items', ['screen' => $screen]) }}"
               class="rounded-xl bg-indigo-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-800">
                {{ __('Manage Items') }}
            </a>
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-2">
            @foreach($screenOptions as $screenKey => $screenName)
                <a href="{{ route('admin.sleepwell.content.hub', ['screen' => $screenKey]) }}"
                   class="rounded-full px-3 py-1.5 text-xs font-semibold transition {{ $screen === $screenKey ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900' : 'border border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-700 dark:border-slate-700 dark:text-slate-200' }}">
                    {{ $screenName }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Sections in this screen') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($sectionCount) }}</p>
            </div>
            <div class="surface-card p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('Items in this screen') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($itemCount) }}</p>
            </div>
        </div>

        <div class="surface-card mt-6 p-4">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Sections') }}</h3>
                <a href="{{ route('admin.sleepwell.home-sections.create') }}"
                   class="rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-primary-700">
                    {{ __('Add Section') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead>
                        <tr class="text-left text-slate-600 dark:text-slate-300">
                            <th class="px-3 py-2">{{ __('Order') }}</th>
                            <th class="px-3 py-2">{{ __('Key') }}</th>
                            <th class="px-3 py-2">{{ __('Type') }}</th>
                            <th class="px-3 py-2">{{ __('Items') }}</th>
                            <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($sections as $section)
                            <tr>
                                <td class="px-3 py-3">{{ $section->sort_order }}</td>
                                <td class="px-3 py-3 font-semibold">{{ $section->section_key }}</td>
                                <td class="px-3 py-3">{{ $section->section_type }}</td>
                                <td class="px-3 py-3">{{ $section->items_count }}</td>
                                <td class="px-3 py-3 text-right">
                                    <a href="{{ route('admin.sleepwell.home-sections.edit', $section) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-slate-500">{{ __('No sections found for this screen.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="surface-card mt-6 p-4">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Items') }}</h3>
                <a href="{{ route('admin.sleepwell.home-items.create') }}"
                   class="rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-primary-700">
                    {{ __('Add Item') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead>
                        <tr class="text-left text-slate-600 dark:text-slate-300">
                            <th class="px-3 py-2">{{ __('Order') }}</th>
                            <th class="px-3 py-2">{{ __('Section') }}</th>
                            <th class="px-3 py-2">{{ __('Title') }}</th>
                            <th class="px-3 py-2">{{ __('Subtitle') }}</th>
                            <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($items as $item)
                            <tr>
                                <td class="px-3 py-3">{{ $item->sort_order }}</td>
                                <td class="px-3 py-3">{{ $item->section?->section_key }}</td>
                                <td class="px-3 py-3 font-semibold">{{ $item->title }}</td>
                                <td class="px-3 py-3">{{ $item->subtitle ?? '-' }}</td>
                                <td class="px-3 py-3 text-right">
                                    <a href="{{ route('admin.sleepwell.home-items.edit', $item) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-slate-500">{{ __('No items found for this screen.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>
