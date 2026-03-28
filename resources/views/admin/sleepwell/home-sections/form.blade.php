@section('meta_title', __($title.' - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">{{ __($title) }}</h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5">
            <a href="{{ route('admin.sleepwell.home-sections.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to Home Sections') }}
            </a>
        </div>

        <div class="surface-card p-6">
            <form method="POST" action="{{ $action }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @if($method !== 'POST') @method($method) @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Section Key') }}</label>
                    <input name="section_key" required value="{{ old('section_key', $section->section_key) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Section Type') }}</label>
                    @php $type = old('section_type', $section->section_type); @endphp
                    <select name="section_type" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                        @foreach(['hero_carousel','grid','horizontal','top_ranked','chips','promo'] as $v)
                            <option value="{{ $v }}" @selected($type === $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">{{ __('Title') }}</label>
                    <input name="title" value="{{ old('title', $section->title) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">{{ __('Subtitle') }}</label>
                    <input name="subtitle" value="{{ old('subtitle', $section->subtitle) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Sort Order') }}</label>
                    <input type="number" name="sort_order" min="0" max="9999" required value="{{ old('sort_order', $section->sort_order ?? 0) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="flex items-center gap-4">
                    <input type="hidden" name="is_active" value="0">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $section->is_active ?? true))>
                        {{ __('Active') }}
                    </label>
                </div>
                <div class="md:col-span-2">
                    <button class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white">{{ __('Save Section') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
