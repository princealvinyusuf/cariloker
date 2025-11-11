<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Blog Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Title') }} *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" required 
                           class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Excerpt') }}</label>
                    <textarea name="excerpt" id="excerpt" rows="3" 
                              class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{
                    addLink() {
                        const textarea = this.$refs.contentTextarea;
                        if (!textarea) {
                            return;
                        }
                        const start = textarea.selectionStart ?? 0;
                        const end = textarea.selectionEnd ?? 0;
                        const rawSelection = textarea.value.slice(start, end);
                        const trimmedSelection = rawSelection.trim();
                        const placeholder = 'Some Text';
                        const placeholderUsed = trimmedSelection.length === 0;
                        const label = placeholderUsed ? placeholder : rawSelection;
                        const promptDefault = trimmedSelection.length > 0 && trimmedSelection.startsWith('http') ? trimmedSelection : 'https://';
                        const urlInput = window.prompt('Enter the link URL', promptDefault);
                        if (!urlInput) {
                            textarea.focus();
                            textarea.setSelectionRange(start, end);
                            return;
                        }
                        let sanitizedUrl = urlInput.trim();
                        if (!sanitizedUrl) {
                            textarea.focus();
                            textarea.setSelectionRange(start, end);
                            return;
                        }
                        const protocolPattern = /^[a-z][a-z0-9+\-.]*:/i;
                        if (!protocolPattern.test(sanitizedUrl)) {
                            sanitizedUrl = 'https://' + sanitizedUrl;
                        }
                        const markdown = '[' + label + '](' + sanitizedUrl + ')';
                        if (typeof textarea.setRangeText === 'function') {
                            textarea.setRangeText(markdown, start, end, 'end');
                        } else {
                            textarea.value = textarea.value.slice(0, start) + markdown + textarea.value.slice(end);
                        }
                        if (placeholderUsed) {
                            const textStart = start + 1;
                            const textEnd = textStart + placeholder.length;
                            textarea.setSelectionRange(textStart, textEnd);
                        } else {
                            const caret = start + markdown.length;
                            textarea.setSelectionRange(caret, caret);
                        }
                        textarea.dispatchEvent(new Event('input'));
                        textarea.focus();
                    }
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label for="content" class="block text-sm font-semibold text-gray-900">{{ __('Content') }} *</label>
                        <button type="button" class="text-sm font-semibold text-violet-600 hover:text-violet-700"
                                x-on:click.prevent="addLink">
                            {{ __('Insert Link') }}
                        </button>
                    </div>
                    <textarea name="content" id="content" x-ref="contentTextarea" rows="15" required 
                              class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">{{ old('content', $blog->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ __('Tip: Select your text and click "Insert Link" or type Markdown like') }}
                        <code class="bg-gray-100 px-1 py-0.5 rounded">[Some Text](https://example.com)</code>
                        {{ __('to add a hyperlink.') }}
                    </p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Featured Image') }}</label>
                    @if($blog->featured_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($blog->featured_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg">
                            <p class="text-sm text-gray-500 mt-1">{{ __('Current image. Upload a new image to replace it.') }}</p>
                        </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" 
                           class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Status') }} *</label>
                        <select name="status" id="status" required 
                                class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Published At') }}</label>
                        <input type="datetime-local" name="published_at" id="published_at" 
                               value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}" 
                               class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-2">
                        {{ __('Update Post') }}
                    </button>
                    <a href="{{ route('admin.blog.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

