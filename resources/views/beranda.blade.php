@section('meta_description', __('beranda.meta'))

<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-white py-12 border-b border-gray-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10">
                <div class="inline-block mb-4">
                    <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        {{ __('beranda.badge') }}
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ __('beranda.headline') }} <span class="text-violet-600">{{ __('beranda.headline_highlight') }}</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    {{ __('beranda.subtagline') }}
                </p>
                <div class="flex items-center justify-center gap-4 mb-8">
                    <a href="{{ route('jobs.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-8 py-3.5 transition-colors text-lg">
                        {{ __('beranda.cta_browse') }}
                    </a>
                    <a href="#how-it-works" class="bg-white border border-violet-600 text-violet-600 hover:bg-violet-50 font-semibold rounded-lg px-8 py-3.5 transition-colors text-lg">
                        {{ __('beranda.cta_how') }}
                    </a>
                </div>
            </div>

            <!-- Tag Cloud (example) -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-8">
                @foreach([__('beranda.tags.1'), __('beranda.tags.2'), __('beranda.tags.3'), __('beranda.tags.4'), __('beranda.tags.5'), __('beranda.tags.6'), __('beranda.tags.7'), __('beranda.tags.8')] as $tag)
                    <span class="px-4 py-2 rounded-full border border-violet-600 text-violet-600 bg-white hover:bg-violet-50 transition-colors text-sm font-medium">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Steps Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" id="how-it-works">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                {{ __('beranda.steps_headline') }} <span class="text-violet-600">{{ __('beranda.steps_highlight') }}</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('beranda.steps_tagline') }}
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-orange-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-plus" style="font-size: 1.5rem; color: #fb923c;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('beranda.step1_title') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('beranda.step1_desc') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-violet-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 1.5rem; color: #7c3aed;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('beranda.step2_title') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('beranda.step2_desc') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-file-arrow-up" style="font-size: 1.5rem; color: #2563eb;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('beranda.step3_title') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('beranda.step3_desc') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-yellow-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-briefcase" style="font-size: 1.5rem; color: #eab308;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('beranda.step4_title') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('beranda.step4_desc') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
