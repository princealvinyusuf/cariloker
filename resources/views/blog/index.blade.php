@section('meta_description', __('blog.meta_description'))
<x-app-layout>
    <div class="bg-white py-12 dark:bg-slate-950">
        <div class="section-container">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                    {{ __('Blog') }}
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                    {{ __('Stay updated with the latest career tips, job market insights, and company news.') }}
                </p>
            </div>

            <!-- Blog Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <article class="surface-card overflow-hidden group transition hover:-translate-y-0.5 hover:border-primary-300">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post) }}">
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                </a>
                            @else
                                <div class="w-full h-48 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                    <i class="fa-solid fa-newspaper text-4xl text-slate-400"></i>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="mb-3 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $post->published_at?->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post) }}">
                                    <h2 class="mb-2 line-clamp-2 text-xl font-bold text-slate-900 transition-colors group-hover:text-primary-700 dark:text-white dark:group-hover:text-primary-300">
                                        {{ $post->title }}
                                    </h2>
                                </a>
                                @if($post->excerpt)
                                    <p class="mb-4 line-clamp-3 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center text-primary-700 hover:text-primary-800 dark:text-primary-300 dark:hover:text-primary-200 font-semibold text-sm">
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
                    <i class="fa-solid fa-newspaper text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
                    <p class="text-slate-600 dark:text-slate-300 text-lg">{{ __('No blog posts available yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

