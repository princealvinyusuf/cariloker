<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-violet-50 via-fuchsia-50 to-white py-16 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ isset($contents['hero_title']) ? $contents['hero_title']->value : 'About Cari Loker' }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ isset($contents['hero_description']) ? $contents['hero_description']->value : 'Your trusted platform for finding the perfect job opportunity.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    {{ isset($contents['about_title']) ? $contents['about_title']->value : 'About Us' }}
                </h2>
                <div class="prose prose-lg max-w-none text-gray-600">
                    {!! nl2br(e(isset($contents['about_content']) ? $contents['about_content']->value : 'We are dedicated to connecting job seekers with their dream careers. Our platform provides a seamless experience for both job seekers and employers.')) !!}
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ isset($contents['mission_title']) ? $contents['mission_title']->value : 'Our Mission' }}</h3>
                <p class="text-gray-600 mb-6">
                    {{ isset($contents['mission_content']) ? $contents['mission_content']->value : 'To empower individuals in their career journey by providing the best job opportunities and resources.' }}
                </p>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ isset($contents['vision_title']) ? $contents['vision_title']->value : 'Our Vision' }}</h3>
                <p class="text-gray-600">
                    {{ isset($contents['vision_content']) ? $contents['vision_content']->value : 'To become the leading job portal in Indonesia, connecting millions of job seekers with opportunities.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Us Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ isset($contents['contact_title']) ? $contents['contact_title']->value : 'Contact Us' }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ isset($contents['contact_description']) ? $contents['contact_description']->value : 'Get in touch with us. We are here to help you with any questions or concerns.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Email -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-envelope text-2xl text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Email') }}</h3>
                    <a href="mailto:{{ isset($contents['contact_email']) ? $contents['contact_email']->value : 'info@cariloker.com' }}" class="text-violet-600 hover:text-violet-700">
                        {{ isset($contents['contact_email']) ? $contents['contact_email']->value : 'info@cariloker.com' }}
                    </a>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-phone text-2xl text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Phone') }}</h3>
                    <a href="tel:{{ isset($contents['contact_phone']) ? $contents['contact_phone']->value : '+62 123 456 7890' }}" class="text-violet-600 hover:text-violet-700">
                        {{ isset($contents['contact_phone']) ? $contents['contact_phone']->value : '+62 123 456 7890' }}
                    </a>
                </div>

                <!-- Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-location-dot text-2xl text-violet-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Address') }}</h3>
                    <p class="text-gray-600">
                        {{ isset($contents['contact_address']) ? $contents['contact_address']->value : 'Jakarta, Indonesia' }}
                    </p>
                </div>
            </div>

            <!-- Additional Contact Info -->
            @if(isset($contents['contact_hours']) && $contents['contact_hours']->value)
                <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Business Hours') }}</h3>
                    <p class="text-gray-600">{{ $contents['contact_hours']->value }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

