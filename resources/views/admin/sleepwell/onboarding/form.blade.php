@section('meta_title', __($title.' - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __($title) }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5">
            <a href="{{ route('admin.sleepwell.onboarding.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to Onboarding Screens') }}
            </a>
        </div>

        <div class="surface-card p-6">
            @if($errors->any())
                <div class="mb-4 rounded-xl bg-rose-100 px-4 py-3 text-sm text-rose-800 dark:bg-rose-900/30 dark:text-rose-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Step Key') }}</label>
                    <input type="text" name="step_key" value="{{ old('step_key', $screen->step_key) }}" required
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Screen Type') }}</label>
                    @php $selectedType = old('screen_type', $screen->screen_type); @endphp
                    <select name="screen_type" required
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @foreach(['welcome', 'single_choice', 'multi_choice', 'slider', 'info', 'email'] as $type)
                            <option value="{{ $type }}" @selected($selectedType === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title', $screen->title) }}" required
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Subtitle') }}</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $screen->subtitle) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Image URL') }}</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $screen->image_url) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Upload Image') }}</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <p class="mt-1 text-xs text-slate-500">{{ __('Upload will override image URL.') }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('CTA Label') }}</label>
                    <input type="text" name="cta_label" value="{{ old('cta_label', $screen->cta_label ?: 'Continue') }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Sort Order') }}</label>
                    <input type="number" name="sort_order" min="0" max="9999" required
                           value="{{ old('sort_order', $screen->sort_order ?? 0) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div class="flex items-center gap-4">
                    <input type="hidden" name="skippable" value="0">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="skippable" value="1" @checked(old('skippable', $screen->skippable ?? true))
                               class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        {{ __('Skippable') }}
                    </label>

                    <input type="hidden" name="is_active" value="0">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $screen->is_active ?? true))
                               class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        {{ __('Active') }}
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Options JSON') }}</label>
                    <textarea name="options_json" rows="7"
                              class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ old('options_json', json_encode($screen->options ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Example choices: {"choices":[{"label":"Sleep All Night","emoji":"⏰"},{"label":"Relax & Unwind","icon_url":"https://..."}]}') }}</p>
                </div>

                <div class="md:col-span-2">
                    <button type="submit"
                            class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                        {{ __('Save Screen') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
