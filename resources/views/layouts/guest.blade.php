<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $metaTitle = trim($__env->yieldContent('meta_title')) ?: 'Masuk - Cari Loker';
            $metaDescription = trim($__env->yieldContent('meta_description')) ?: 'Masuk ke Cari Loker untuk melamar pekerjaan dan mengelola akun Anda.';
            $canonicalUrl = trim($__env->yieldContent('canonical_url')) ?: url()->current();
            $ogImage = trim($__env->yieldContent('og_image')) ?: asset('image/cariloker.png');
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-adsense-account" content="ca-pub-6811930762522149">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="noindex,nofollow">
        <link rel="canonical" href="{{ $canonicalUrl }}">

        <meta property="og:site_name" content="Cari Loker">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $ogImage }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $ogImage }}">

        <title>{{ $metaTitle }}</title>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-CC928GJ6D0"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-CC928GJ6D0');
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="icon" type="image/png" href="{{ asset('image/cariloker.png') }}">

        <!-- Scripts -->
        <script>
            // Apply theme early to avoid FOUC (defaults to light mode)
            (function(){
                try {
                    var saved = localStorage.getItem('theme_v2');
                    // Default to light mode - only use dark if explicitly saved as 'dark'
                    if (saved === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) {}
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/sticky-search.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-100 px-4 py-10 dark:bg-slate-950">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,rgba(31,123,255,0.14),transparent_45%),radial-gradient(circle_at_80%_80%,rgba(34,211,238,0.14),transparent_45%)]"></div>

            <div class="relative w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="grid md:grid-cols-2">
                    <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-primary-700 via-primary-600 to-accent-600 p-10 text-white">
                        <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-xl font-bold tracking-tight">
                            <span>Cari Loker</span>
                        </a>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-white/80">{{ __('Karier') }}</p>
                            <h1 class="mt-3 text-3xl font-bold leading-tight">{{ __('Temukan pekerjaan yang relevan dengan tujuanmu.') }}</h1>
                            <p class="mt-4 text-sm text-white/85">{{ __('Jelajahi lowongan terbaru, bandingkan perusahaan, dan kirim lamaran lebih cepat.') }}</p>
                        </div>
                        <p class="text-sm text-white/70">{{ __('Trusted by job seekers across Indonesia') }}</p>
                    </div>

                    <div class="px-6 py-8 sm:px-10 sm:py-10">
                        <div class="mb-6 md:hidden">
                            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-lg font-bold text-primary-700 dark:text-primary-300">
                                <span>Cari Loker</span>
                            </a>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
