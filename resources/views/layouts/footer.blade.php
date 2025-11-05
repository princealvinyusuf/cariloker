<footer class="bg-gray-900 text-gray-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl font-bold">
                        <span class="text-white">Cari</span><span class="text-violet-400"> Loker</span>
                    </span>
                </div>
                <p class="text-sm text-gray-400 mb-4">{{ __('Your trusted platform for finding the perfect job opportunity.') }}</p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-violet-600 flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-violet-600 flex items-center justify-center transition-colors">
                        <i class="fab fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-violet-600 flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-violet-600 flex items-center justify-center transition-colors">
                        <i class="fab fa-youtube text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-semibold mb-4">{{ __('Quick Links') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">{{ __('Find Jobs') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('Browse Companies') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('About Us') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('Contact Us') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('Blog') }}</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-white font-semibold mb-4">{{ __('Legal') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ route('terms-of-service') }}" class="hover:text-white transition-colors">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ route('cookie-policy') }}" class="hover:text-white transition-colors">{{ __('Cookie Policy') }}</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">{{ __('FAQ') }}</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-white font-semibold mb-4">{{ __('Contact Us') }}</h3>
                <ul class="space-y-2 text-sm">
                    @php
                        $contactEmail = \App\Models\AboutPageContent::getContent('contact_email', 'info@cariloker.com');
                        $contactPhone = \App\Models\AboutPageContent::getContent('contact_phone', '+62 123 456 7890');
                        $contactAddress = \App\Models\AboutPageContent::getContent('contact_address', 'Jakarta, Indonesia');
                    @endphp
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-envelope mt-1 text-violet-400"></i>
                        <a href="mailto:{{ $contactEmail }}" class="hover:text-white transition-colors">{{ $contactEmail }}</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-phone mt-1 text-violet-400"></i>
                        <a href="tel:{{ $contactPhone }}" class="hover:text-white transition-colors">{{ $contactPhone }}</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot mt-1 text-violet-400"></i>
                        <span>{{ $contactAddress }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} Cari Loker. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>

