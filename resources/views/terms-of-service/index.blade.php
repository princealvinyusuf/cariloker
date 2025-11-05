<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-16 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ isset($contents['hero_title']) ? $contents['hero_title']->value : 'Terms of Service' }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ isset($contents['hero_description']) ? $contents['hero_description']->value : 'Please read these terms carefully before using our platform.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Terms of Service Content -->
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

            @if(isset($contents['acceptance_of_terms']) && $contents['acceptance_of_terms']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['acceptance_of_terms_title']) ? $contents['acceptance_of_terms_title']->value : 'Acceptance of Terms' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['acceptance_of_terms']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['use_of_service']) && $contents['use_of_service']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['use_of_service_title']) ? $contents['use_of_service_title']->value : 'Use of Service' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['use_of_service']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['user_accounts']) && $contents['user_accounts']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['user_accounts_title']) ? $contents['user_accounts_title']->value : 'User Accounts' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['user_accounts']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['job_postings']) && $contents['job_postings']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['job_postings_title']) ? $contents['job_postings_title']->value : 'Job Postings and Applications' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['job_postings']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['intellectual_property']) && $contents['intellectual_property']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['intellectual_property_title']) ? $contents['intellectual_property_title']->value : 'Intellectual Property' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['intellectual_property']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['user_conduct']) && $contents['user_conduct']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['user_conduct_title']) ? $contents['user_conduct_title']->value : 'User Conduct' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['user_conduct']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['privacy']) && $contents['privacy']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['privacy_title']) ? $contents['privacy_title']->value : 'Privacy and Data Protection' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['privacy']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['limitation_of_liability']) && $contents['limitation_of_liability']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['limitation_of_liability_title']) ? $contents['limitation_of_liability_title']->value : 'Limitation of Liability' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['limitation_of_liability']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['termination']) && $contents['termination']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['termination_title']) ? $contents['termination_title']->value : 'Termination' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['termination']->value)) !!}
                    </div>
                </div>
            @endif

            @if(isset($contents['changes_to_terms']) && $contents['changes_to_terms']->value)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ isset($contents['changes_to_terms_title']) ? $contents['changes_to_terms_title']->value : 'Changes to Terms' }}
                    </h2>
                    <div class="text-gray-600">
                        {!! nl2br(e($contents['changes_to_terms']->value)) !!}
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
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('Questions About Our Terms of Service?') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('If you have any questions about our terms of service, please contact us.') }}</p>
                <a href="{{ route('about') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors">
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

