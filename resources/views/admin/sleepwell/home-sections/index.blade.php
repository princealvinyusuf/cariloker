@section('meta_title', __('SleepWell Home Sections - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('SleepWell Home Sections') }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
            <a href="{{ route('admin.sleepwell.home-sections.create') }}"
               class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                {{ __('Add Section') }}
            </a>
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-2">
            @foreach($screenOptions as $screenKey => $screenLabel)
                @php
                    $screenHref = $screenKey === 'all'
                        ? route('admin.sleepwell.home-sections.index')
                        : route('admin.sleepwell.content.sections', ['screen' => $screenKey]);
                @endphp
                <a href="{{ $screenHref }}"
                   class="rounded-full px-3 py-1.5 text-xs font-semibold transition {{ $selectedScreen === $screenKey ? 'bg-indigo-600 text-white' : 'border border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-700 dark:border-slate-700 dark:text-slate-200' }}">
                    {{ $screenLabel }}
                </a>
            @endforeach
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.sleepwell.home-sections.export', ['screen' => $selectedScreen]) }}"
               class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                {{ __('Export JSON') }}
            </a>
            <details class="rounded-lg border border-slate-200 p-2 text-xs dark:border-slate-700">
                <summary class="cursor-pointer font-semibold">{{ __('Bulk Import JSON') }}</summary>
                <form method="POST" action="{{ route('admin.sleepwell.home-sections.import') }}" class="mt-2">
                    @csrf
                    <textarea name="sections_json" rows="6" class="w-full rounded-xl border-slate-300 text-xs dark:border-slate-700 dark:bg-slate-900" placeholder='[{"section_key":"featured_content","section_type":"hero_carousel"}]'></textarea>
                    <button class="mt-2 rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white">{{ __('Import') }}</button>
                </form>
            </details>
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
                        <th class="px-3 py-2">{{ __('Key') }}</th>
                        <th class="px-3 py-2">{{ __('Type') }}</th>
                        <th class="px-3 py-2">{{ __('Title') }}</th>
                        <th class="px-3 py-2">{{ __('Status') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($sections as $section)
                        <tr>
                            <td class="px-3 py-3">{{ $section->sort_order }}</td>
                            <td class="px-3 py-3 font-semibold">{{ $section->section_key }}</td>
                            <td class="px-3 py-3">{{ $section->section_type }}</td>
                            <td class="px-3 py-3">{{ $section->title ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $section->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="px-3 py-3 text-right">
                                <a href="{{ route('admin.sleepwell.home-sections.edit', $section) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.sleepwell.home-sections.destroy', $section) }}" class="inline-block ml-3" onsubmit="return confirm('Delete this section?')">
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
        <div class="mt-4">{{ $sections->links() }}</div>
    </div>
</x-app-layout>
