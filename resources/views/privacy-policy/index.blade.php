<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-16 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ isset($contents['hero_title']) ? $contents['hero_title']->value : 'Privacy Policy' }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ isset($contents['hero_description']) ? $contents['hero_description']->value : 'Your privacy is important to us. Learn how we collect, use, and protect your information.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Content -->
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

            @if(isset($contents['information_we_collect']) && $contents['information_we_collect']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['information_we_collect_title']) ? $contents['information_we_collect_title']->value : 'Information We Collect' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['information_we_collect']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['how_we_use']) && $contents['how_we_use']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['how_we_use_title']) ? $contents['how_we_use_title']->value : 'How We Use Your Information' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['how_we_use']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['information_sharing']) && $contents['information_sharing']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['information_sharing_title']) ? $contents['information_sharing_title']->value : 'Information Sharing and Disclosure' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['information_sharing']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['data_security']) && $contents['data_security']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['data_security_title']) ? $contents['data_security_title']->value : 'Data Security' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['data_security']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['your_rights']) && $contents['your_rights']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['your_rights_title']) ? $contents['your_rights_title']->value : 'Your Rights' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['your_rights']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['cookies']) && $contents['cookies']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['cookies_title']) ? $contents['cookies_title']->value : 'Cookies and Tracking Technologies' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['cookies']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['third_party_services']) && $contents['third_party_services']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['third_party_services_title']) ? $contents['third_party_services_title']->value : 'Third-Party Services' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['third_party_services']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['children_privacy']) && $contents['children_privacy']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['children_privacy_title']) ? $contents['children_privacy_title']->value : "Children's Privacy" }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['children_privacy']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['policy_changes']) && $contents['policy_changes']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['policy_changes_title']) ? $contents['policy_changes_title']->value : 'Changes to This Privacy Policy' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['policy_changes']->value)) !!}
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
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('Questions About Our Privacy Policy?') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('If you have any questions about our privacy practices, please contact us.') }}</p>
                <a href="{{ route('about') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

