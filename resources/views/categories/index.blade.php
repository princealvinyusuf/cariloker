<x-app-layout>
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                    <span class="text-violet-600">{{ __('Countless Career Options') }}</span> {{ __('Are Waiting For You to Explore') }}
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Browse through all job categories and find the perfect opportunity for your career.') }}
                </p>
            </div>

            <!-- Categories Grid -->
            @if($categories->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 mb-8">
                    @foreach($categories as $category)
                        <a href="{{ route('jobs.index', ['category' => $category->slug, 'list' => '1']) }}" 
                           class="bg-white rounded-2xl p-6 text-center hover:shadow-lg hover:scale-105 hover:bg-violet-50 transition-all group">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-violet-50 flex items-center justify-center group-hover:bg-violet-100 transition-colors">
                                <i class="fa-solid fa-briefcase text-xl text-violet-600 group-hover:text-violet-600"></i>
                            </div>
                            <h3 class="font-bold text-sm mb-1 text-gray-900 group-hover:text-violet-600">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-500 group-hover:text-gray-600">
                                {{ $category->jobs_count ?? 0 }}+ {{ __('openings') }}
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">{{ __('No categories available at the moment.') }}</p>
                </div>
            @endif

            <!-- Back to Jobs Button -->
            <div class="text-center mt-8">
                <a href="{{ route('jobs.index') }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                    {{ __('Back to Jobs') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

