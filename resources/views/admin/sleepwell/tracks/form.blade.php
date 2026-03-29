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
            <a href="{{ route('admin.sleepwell.tracks.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to Tracks') }}
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

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ old('title', $track->title) }}" required
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Subtitle') }}</label>
                    @php
                        $selectedSubtitle = old('subtitle', $track->subtitle);
                        $options = $subtitleOptions ?? [];
                        if (!empty($selectedSubtitle) && !in_array($selectedSubtitle, $options, true)) {
                            $options[] = $selectedSubtitle;
                        }
                    @endphp
                    <select name="subtitle"
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        <option value="">{{ __('Select subtitle from items') }}</option>
                        @foreach($options as $option)
                            <option value="{{ $option }}" @selected($selectedSubtitle === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Category') }}</label>
                    <select name="category" required
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @php
                            $selectedCategory = old('category', $track->category);
                        @endphp
                        @foreach(['whisper', 'no_talking', 'rain', 'roleplay'] as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Sound Type') }}</label>
                    <input type="text" name="sound_type" value="{{ old('sound_type', $track->sound_type) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Duration (seconds)') }}</label>
                    <input type="number" name="duration_seconds" min="30" max="86400" required
                           value="{{ old('duration_seconds', $track->duration_seconds ?: 1800) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Stream URL') }}</label>
                    <input type="url" name="stream_url" value="{{ old('stream_url', $track->stream_url) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <p class="mt-1 text-xs text-slate-500">{{ __('Provide URL or upload an audio file below.') }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Cover Image URL') }}</label>
                    <input type="url" name="cover_image_url" value="{{ old('cover_image_url', $track->cover_image_url) }}"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Upload Audio File') }}</label>
                    <input type="file" name="audio_file" accept=".mp3,.wav,.m4a,.ogg"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('Upload Cover Image') }}</label>
                    <input type="file" name="cover_image_file" accept="image/*"
                           class="w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                </div>

                <div class="flex items-center gap-4">
                    <input type="hidden" name="talking" value="0">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="talking" value="1" @checked(old('talking', $track->talking))
                               class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        {{ __('Talking') }}
                    </label>
                </div>

                <div class="flex items-center gap-4">
                    <input type="hidden" name="is_active" value="0">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $track->is_active ?? true))
                               class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        {{ __('Active') }}
                    </label>
                </div>

                <div class="md:col-span-2">
                    <button type="submit"
                            class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                        {{ __('Save Track') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
