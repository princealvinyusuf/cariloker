<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="{{ route('blog.index') }}" class="text-violet-600 text-sm mb-4 inline-block">← {{ __('Back to Blog') }}</a>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $blogPost->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span>{{ $blogPost->published_at?->format('d M Y') }}</span>
                <span>•</span>
                <span>{{ $blogPost->user->name }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <main class="lg:col-span-8">
                @if($blogPost->featured_image)
                    <img src="{{ Storage::disk('public')->url($blogPost->featured_image) }}" alt="{{ $blogPost->title }}" class="w-full rounded-2xl mb-6">
                @endif
                <div class="prose max-w-none">
                    {!! nl2br(e($blogPost->content)) !!}
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recent Posts') }}</h3>
                    @if($recentPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                                <a href="{{ route('blog.show', $post) }}" class="block group">
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-violet-600 transition-colors line-clamp-2">
                                        {{ $post->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $post->published_at?->format('d M Y') }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ __('No recent posts.') }}</p>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>

