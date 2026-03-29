@section('meta_title', __($title.' - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">{{ __($title) }}</h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5">
            <a href="{{ route('admin.sleepwell.ad-placements.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to Ad Placements') }}
            </a>
        </div>

        <div class="surface-card p-6">
            <form method="POST" action="{{ $action }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @if($method !== 'POST') @method($method) @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Screen') }}</label>
                    <input name="screen" required value="{{ old('screen', $placement->screen) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Slot Key') }}</label>
                    <input name="slot_key" required value="{{ old('slot_key', $placement->slot_key) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Format') }}</label>
                    <input name="format" required value="{{ old('format', $placement->format ?? 'banner') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Frequency Cap') }}</label>
                    <input type="number" min="0" max="999" name="frequency_cap" required value="{{ old('frequency_cap', $placement->frequency_cap ?? 0) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Countries (comma separated)') }}</label>
                    <input name="countries" value="{{ old('countries', $placement->countries) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Priority') }}</label>
                    <input type="number" min="0" max="9999" name="priority" required value="{{ old('priority', $placement->priority ?? 0) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Android Ad Unit ID') }}</label>
                    <input name="ad_unit_id_android" value="{{ old('ad_unit_id_android', $placement->ad_unit_id_android) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('iOS Ad Unit ID') }}</label>
                    <input name="ad_unit_id_ios" value="{{ old('ad_unit_id_ios', $placement->ad_unit_id_ios) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="flex items-center gap-4">
                    <input type="hidden" name="enabled" value="0">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="enabled" value="1" @checked(old('enabled', $placement->enabled ?? true))>
                        {{ __('Enabled') }}
                    </label>
                </div>
                <div class="md:col-span-2">
                    <button class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Save Placement') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
