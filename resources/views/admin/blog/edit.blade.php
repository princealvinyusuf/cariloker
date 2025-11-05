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

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Content') }} *</label>
                    <textarea name="content" id="content" rows="15" required 
                              class="w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500">{{ old('content', $blog->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Featured Image') }}</label>
                    @if($blog->featured_image)
                        <div class="mb-2">
                            <img src="{{ Storage::disk('public')->url($blog->featured_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg">
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

