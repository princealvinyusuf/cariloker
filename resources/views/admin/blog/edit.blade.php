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
                    replaceSelection(transformer) {
                        const textarea = this.$refs.contentTextarea;
                        if (!textarea) {
                            return;
                        }
                        const start = textarea.selectionStart ?? 0;
                        const end = textarea.selectionEnd ?? 0;
                        const value = textarea.value;
                        const selection = value.slice(start, end);
                        const result = transformer({ selection, start, end, value });
                        if (!result || typeof result.text !== 'string') {
                            textarea.focus();
                            textarea.setSelectionRange(start, end);
                            return;
                        }
                        const text = result.text;
                        if (typeof textarea.setRangeText === 'function') {
                            textarea.setRangeText(text, start, end, 'end');
                        } else {
                            textarea.value = value.slice(0, start) + text + value.slice(end);
                        }
                        const selectStartOffset = typeof result.selectStartOffset === 'number'
                            ? result.selectStartOffset
                            : text.length;
                        const selectEndOffset = typeof result.selectEndOffset === 'number'
                            ? result.selectEndOffset
                            : text.length;
                        textarea.setSelectionRange(start + selectStartOffset, start + selectEndOffset);
                        textarea.dispatchEvent(new Event('input'));
                        textarea.focus();
                    },
                    wrapSelection(prefix, suffix, placeholder) {
                        this.replaceSelection(({ selection }) => {
                            const trimmed = selection.trim();
                            if (!trimmed.length) {
                                const text = prefix + placeholder + suffix;
                                return {
                                    text,
                                    selectStartOffset: prefix.length,
                                    selectEndOffset: prefix.length + placeholder.length,
                                };
                            }
                            return {
                                text: prefix + selection + suffix,
                            };
                        });
                    },
                    addBold() {
                        this.wrapSelection('**', '**', '{{ __('Bold text') }}');
                    },
                    addItalic() {
                        this.wrapSelection('*', '*', '{{ __('Italic text') }}');
                    },
                    addLink() {
                        this.replaceSelection(({ selection }) => {
                            const trimmed = selection.trim();
                            const placeholder = 'Some Text';
                            const placeholderUsed = trimmed.length === 0;
                            const label = placeholderUsed ? placeholder : selection;
                            const promptDefault = trimmed.length > 0 && trimmed.startsWith('http') ? trimmed : 'https://';
                            const urlInput = window.prompt('Enter the link URL', promptDefault);
                            if (!urlInput) {
                                return null;
                            }
                            let sanitizedUrl = urlInput.trim();
                            if (!sanitizedUrl) {
                                return null;
                            }
                            const protocolPattern = /^[a-z][a-z0-9+\-.]*:/i;
                            if (!protocolPattern.test(sanitizedUrl)) {
                                sanitizedUrl = 'https://' + sanitizedUrl;
                            }
                            const markdown = '[' + label + '](' + sanitizedUrl + ')';
                            if (placeholderUsed) {
                                return {
                                    text: markdown,
                                    selectStartOffset: 1,
                                    selectEndOffset: 1 + placeholder.length,
                                };
                            }
                            return { text: markdown };
                        });
                    },
                    addHeading(level) {
                        const safeLevel = Math.min(Math.max(level, 2), 6);
                        const prefix = '#'.repeat(safeLevel) + ' ';
                        this.replaceSelection(({ selection }) => {
                            const trimmed = selection.trim();
                            if (!trimmed.length) {
                                const placeholder = 'Heading ' + safeLevel;
                                const text = prefix + placeholder;
                                return {
                                    text,
                                    selectStartOffset: prefix.length,
                                    selectEndOffset: prefix.length + placeholder.length,
                                };
                            }
                            const normalized = selection.replace(/\r/g, '');
                            const hasTrailingNewline = normalized.endsWith('\n');
                            const lines = normalized.split('\n');
                            if (hasTrailingNewline) {
                                lines.pop();
                            }
                            const transformed = lines.map((line) => {
                                const cleaned = line.replace(/^\s*#{1,6}\s+/, '').trim();
                                return prefix + (cleaned.length ? cleaned : 'Heading ' + safeLevel);
                            }).join('\n');
                            const text = transformed + (hasTrailingNewline ? '\n' : '');
                            return { text };
                        });
                    },
                    addBulletList() {
                        this.replaceSelection(({ selection }) => {
                            const trimmed = selection.trim();
                            if (!trimmed.length) {
                                const text = '- List item 1\n- List item 2\n- List item 3';
                                const newlineIndex = text.indexOf('\n');
                                const endOffset = newlineIndex === -1 ? text.length : newlineIndex;
                                return {
                                    text,
                                    selectStartOffset: 2,
                                    selectEndOffset: endOffset,
                                };
                            }
                            const normalized = selection.replace(/\r/g, '');
                            const hasTrailingNewline = normalized.endsWith('\n');
                            const lines = normalized.split('\n');
                            if (hasTrailingNewline) {
                                lines.pop();
                            }
                            const transformed = lines.map((line) => {
                                const cleaned = line.replace(/^\s*([-*+]\s+)/, '').trim();
                                return '- ' + (cleaned.length ? cleaned : 'List item');
                            }).join('\n');
                            const text = transformed + (hasTrailingNewline ? '\n' : '');
                            return { text };
                        });
                    },
                    addNumberedList() {
                        this.replaceSelection(({ selection }) => {
                            const trimmed = selection.trim();
                            if (!trimmed.length) {
                                const text = '1. List item 1\n2. List item 2\n3. List item 3';
                                const newlineIndex = text.indexOf('\n');
                                const endOffset = newlineIndex === -1 ? text.length : newlineIndex;
                                return {
                                    text,
                                    selectStartOffset: 3,
                                    selectEndOffset: endOffset,
                                };
                            }
                            const normalized = selection.replace(/\r/g, '');
                            const hasTrailingNewline = normalized.endsWith('\n');
                            const lines = normalized.split('\n');
                            if (hasTrailingNewline) {
                                lines.pop();
                            }
                            const transformed = lines.map((line, index) => {
                                const cleaned = line.replace(/^\s*\d+\.\s+/, '').trim();
                                const label = cleaned.length ? cleaned : 'List item ' + (index + 1);
                                return (index + 1) + '. ' + label;
                            }).join('\n');
                            const text = transformed + (hasTrailingNewline ? '\n' : '');
                            return { text };
                        });
                    }
                }">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                        <label for="content" class="block text-sm font-semibold text-gray-900">{{ __('Content') }} *</label>
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="flex items-center gap-1">
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addBold">
                                    {{ __('Bold') }}
                                </button>
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addItalic">
                                    {{ __('Italic') }}
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addHeading(2)">
                                    {{ __('H2') }}
                                </button>
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addHeading(3)">
                                    {{ __('H3') }}
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addBulletList">
                                    {{ __('• List') }}
                                </button>
                                <button type="button" class="text-xs font-semibold px-3 py-1 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50"
                                        x-on:click.prevent="addNumberedList">
                                    {{ __('1. List') }}
                                </button>
                            </div>
                            <button type="button" class="text-xs font-semibold px-3 py-1 border border-violet-100 text-violet-600 hover:bg-violet-50 rounded-md"
                                    x-on:click.prevent="addLink">
                                {{ __('Insert Link') }}
                            </button>
                        </div>
                    </div>
                    <textarea name="content" id="content" x-ref="contentTextarea" rows="15" required 
                              class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">{{ old('content', $blog->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ __('Tip: Select text and use the toolbar or type Markdown like') }}
                        <code class="bg-gray-100 px-1 py-0.5 rounded">**{{ __('bold') }}**</code>,
                        <code class="bg-gray-100 px-1 py-0.5 rounded">*{{ __('italic') }}*</code>,
                        <code class="bg-gray-100 px-1 py-0.5 rounded">[{{ __('Some Text') }}](https://example.com)</code>
                        {{ __('to format your content.') }}
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

