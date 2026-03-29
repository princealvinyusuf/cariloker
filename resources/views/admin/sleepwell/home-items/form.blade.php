@section('meta_title', __($title.' - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">{{ __($title) }}</h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5">
            <a href="{{ route('admin.sleepwell.home-items.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to Home Items') }}
            </a>
        </div>

        <div class="surface-card p-6">
            @if(session('status'))
                <div class="mb-4 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-xl bg-rose-100 px-4 py-3 text-sm text-rose-800 dark:bg-rose-900/30 dark:text-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @if($method !== 'POST') @method($method) @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Section') }}</label>
                    @php $sectionId = old('section_id', $item->section_id); @endphp
                    <select name="section_id" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" @selected((int)$sectionId === (int)$section->id)>{{ $section->section_key }} ({{ $section->section_type }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Tag') }}</label>
                    <input name="tag" value="{{ old('tag', $item->tag) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">{{ __('Title') }}</label>
                    <input name="title" required value="{{ old('title', $item->title) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">{{ __('Subtitle') }}</label>
                    <input name="subtitle" value="{{ old('subtitle', $item->subtitle) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Image URL') }}</label>
                    <input name="image_url" value="{{ old('image_url', $item->image_url) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Icon URL') }}</label>
                    <input name="icon_url" value="{{ old('icon_url', $item->icon_url) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Upload Image') }}</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Upload Icon') }}</label>
                    <input type="file" name="icon_file" accept="image/*" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('CTA Label') }}</label>
                    <input name="cta_label" value="{{ old('cta_label', $item->cta_label) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Audio Track ID') }}</label>
                    <input type="number" name="audio_track_id" value="{{ old('audio_track_id', $item->audio_track_id) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">{{ __('Meta JSON') }}</label>
                    <textarea name="meta_json" rows="6" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">{{ old('meta_json', json_encode($item->meta ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Sort Order') }}</label>
                    <input type="number" min="0" max="9999" required name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Publish At') }}</label>
                    <input type="datetime-local" name="publish_at" value="{{ old('publish_at', optional($item->publish_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ __('Unpublish At') }}</label>
                    <input type="datetime-local" name="unpublish_at" value="{{ old('unpublish_at', optional($item->unpublish_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
                <div class="flex items-center gap-4">
                    <input type="hidden" name="is_active" value="0">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
                        {{ __('Active') }}
                    </label>
                </div>
                <div class="md:col-span-2 flex items-center justify-between gap-3">
                    <button class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white">
                        {{ __('Save Item') }}
                    </button>

                    @if($item->exists)
                        <form method="POST"
                              action="{{ route('admin.sleepwell.home-items.destroy', $item) }}"
                              onsubmit="return confirm('{{ __('Delete this item? This action cannot be undone.') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="rounded-xl border border-rose-300 px-5 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-600 dark:text-rose-300 dark:hover:bg-rose-900/20">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
