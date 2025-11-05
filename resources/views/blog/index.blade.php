<x-app-layout>
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                    {{ __('Blog') }}
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Stay updated with the latest career tips, job market insights, and company news.') }}
                </p>
            </div>

            <!-- Blog Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all group">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post) }}">
                                    <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-violet-50 to-fuchsia-50 flex items-center justify-center">
                                    <i class="fa-solid fa-newspaper text-4xl text-violet-400"></i>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                    <span>{{ $post->published_at?->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post) }}">
                                    <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-violet-600 transition-colors line-clamp-2">
                                        {{ $post->title }}
                                    </h2>
                                </a>
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center text-violet-600 hover:text-violet-700 font-semibold text-sm">
                                    {{ __('Read More') }} →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fa-solid fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">{{ __('No blog posts available yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

