<footer class="mt-20 border-t border-slate-200 bg-slate-950 text-slate-300 dark:border-slate-800">
    <div class="section-container py-14">
        @php
            $contactEmail = \App\Models\AboutPageContent::getContent('contact_email', 'info@cariloker.com');
            $contactPhone = \App\Models\AboutPageContent::getContent('contact_phone', '+62 123 456 7890');
            $contactAddress = \App\Models\AboutPageContent::getContent('contact_address', 'Jakarta, Indonesia');
        @endphp

        <div class="grid gap-10 lg:grid-cols-4">
            <div>
                <p class="text-2xl font-extrabold tracking-tight text-white"><span class="text-primary-400">Cari</span> Loker</p>
                <p class="mt-4 max-w-sm text-sm text-slate-400">{{ __('Platform pencarian kerja modern untuk menemukan peluang terbaik dengan lebih cepat dan tepat.') }}</p>
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 text-slate-300 transition hover:border-primary-400 hover:text-primary-300"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 text-slate-300 transition hover:border-primary-400 hover:text-primary-300"><i class="fab fa-linkedin-in text-sm"></i></a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 text-slate-300 transition hover:border-primary-400 hover:text-primary-300"><i class="fab fa-twitter text-sm"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-100">{{ __('Explore') }}</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('jobs.index', ['list' => '1']) }}" class="text-slate-400 transition hover:text-white">{{ __('Find Jobs') }}</a></li>
                    <li><a href="{{ route('companies.index') }}" class="text-slate-400 transition hover:text-white">{{ __('Browse Companies') }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-slate-400 transition hover:text-white">{{ __('Career Blog') }}</a></li>
                    <li><a href="{{ route('cv.reviewer') }}" class="text-slate-400 transition hover:text-white">{{ __('Bedan CV Gratis') }}</a></li>
                    <li><a href="{{ route('about') }}" class="text-slate-400 transition hover:text-white">{{ __('About Us') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-100">{{ __('Support') }}</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('faq') }}" class="text-slate-400 transition hover:text-white">{{ __('FAQ') }}</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="text-slate-400 transition hover:text-white">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ route('terms-of-service') }}" class="text-slate-400 transition hover:text-white">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ route('cookie-policy') }}" class="text-slate-400 transition hover:text-white">{{ __('Cookie Policy') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-100">{{ __('Contact') }}</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-400">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-envelope mt-1 text-primary-400"></i>
                        <a href="mailto:{{ $contactEmail }}" class="transition hover:text-white">{{ $contactEmail }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-phone mt-1 text-primary-400"></i>
                        <a href="tel:{{ $contactPhone }}" class="transition hover:text-white">{{ $contactPhone }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-primary-400"></i>
                        <span>{{ $contactAddress }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-slate-800 pt-6 text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} Cari Loker. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>

