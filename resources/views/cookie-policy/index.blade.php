<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-16 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ isset($contents['hero_title']) ? $contents['hero_title']->value : 'Cookie Policy' }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ isset($contents['hero_description']) ? $contents['hero_description']->value : 'Learn how we use cookies and similar technologies on our website.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Cookie Policy Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="prose prose-lg max-w-none">
            @if(isset($contents['last_updated']) && $contents['last_updated']->value)
                <p class="text-sm text-gray-500 mb-8">
                    <strong>{{ __('Last Updated:') }}</strong> {{ $contents['last_updated']->value }}
                </p>
            @endif

            @if(isset($contents['introduction']) && $contents['introduction']->value)
                <div class="mb-8">
                    {!! nl2br(e($contents['introduction']->value)) !!}
                </div>
            @endif

            @if(isset($contents['what_are_cookies']) && $contents['what_are_cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['what_are_cookies_title']) ? $contents['what_are_cookies_title']->value : 'What Are Cookies?' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['what_are_cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['how_we_use_cookies']) && $contents['how_we_use_cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['how_we_use_cookies_title']) ? $contents['how_we_use_cookies_title']->value : 'How We Use Cookies' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['how_we_use_cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['types_of_cookies']) && $contents['types_of_cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['types_of_cookies_title']) ? $contents['types_of_cookies_title']->value : 'Types of Cookies We Use' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['types_of_cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['manage_cookies']) && $contents['manage_cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['manage_cookies_title']) ? $contents['manage_cookies_title']->value : 'Managing Cookies' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['manage_cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['third_party_cookies']) && $contents['third_party_cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['third_party_cookies_title']) ? $contents['third_party_cookies_title']->value : 'Third-Party Cookies' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['third_party_cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['contact_info']) && $contents['contact_info']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['contact_info_title']) ? $contents['contact_info_title']->value : 'Contact Us' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['contact_info']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['conclusion']) && $contents['conclusion']->value)
                <div class="mb-8">
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['conclusion']->value)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Contact Section -->
        <div class="mt-12 bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-2xl p-8 border border-violet-100">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('Questions About Our Cookie Policy?') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('If you have any questions about our cookie policy, please contact us.') }}</p>
                <a href="{{ route('about') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

