<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
            $baseMetaDescription = 'Cari Loker - Temukan peluang kerja terbaik dan wujudkan impian karier Anda. Update info lowongan terbaru dengan cepat, mudah, dan terpercaya.';

            $computedTitle = match ($routeName) {
                'beranda' => 'Cari Loker - Portal Lowongan Kerja Terbaru di Indonesia',
                'jobs.index' => 'Lowongan Kerja Terbaru - Cari Loker',
                'jobs.show' => isset($job) ? ($job->title . ' - ' . ($job->company->name ?? 'Cari Loker')) : 'Detail Lowongan Kerja - Cari Loker',
                'companies.index' => 'Daftar Perusahaan Terbaik - Cari Loker',
                'companies.show' => isset($company) ? ($company->name . ' - Profil Perusahaan') : 'Profil Perusahaan - Cari Loker',
                'blog.index' => 'Blog Karier & Tips Kerja - Cari Loker',
                'blog.show' => isset($blogPost) ? ($blogPost->title . ' - Blog Cari Loker') : 'Artikel Karier - Cari Loker',
                'about' => 'Tentang Kami - Cari Loker',
                'faq' => 'FAQ - Cari Loker',
                'terms-of-service' => 'Syarat & Ketentuan - Cari Loker',
                'privacy-policy' => 'Kebijakan Privasi - Cari Loker',
                'cookie-policy' => 'Kebijakan Cookie - Cari Loker',
                default => config('app.name', 'Cari Loker'),
            };

            $metaTitle = trim($__env->yieldContent('meta_title')) ?: $computedTitle;
            $metaDescription = trim($__env->yieldContent('meta_description')) ?: $baseMetaDescription;
            $canonicalUrl = trim($__env->yieldContent('canonical_url')) ?: url()->current();
            $ogImage = trim($__env->yieldContent('og_image')) ?: asset('image/cariloker.png');
            $ogType = trim($__env->yieldContent('og_type')) ?: 'website';

            $queryWithoutPage = request()->except(['page']);
            $isFilteredDirectoryPage = in_array($routeName, ['jobs.index', 'companies.index'], true) && !empty($queryWithoutPage);
            $isPaginatedPage = request()->integer('page', 1) > 1;

            if ($isFilteredDirectoryPage) {
                $canonicalUrl = in_array($routeName, ['jobs.index', 'companies.index'], true)
                    ? route($routeName)
                    : $canonicalUrl;
            }

            $defaultRobots = 'index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1';
            $metaRobots = trim($__env->yieldContent('meta_robots')) ?: (($isFilteredDirectoryPage || $isPaginatedPage) ? 'noindex,follow' : $defaultRobots);

            $websiteJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'Cari Loker',
                'url' => rtrim(config('app.url'), '/'),
                'inLanguage' => app()->getLocale() === 'id' ? 'id-ID' : 'en-US',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('jobs.index') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ];
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-adsense-account" content="ca-pub-6811930762522149">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="{{ $metaRobots }}">
        <meta name="author" content="Cari Loker">
        <meta name="language" content="{{ app()->getLocale() === 'id' ? 'Indonesian' : 'English' }}">

        <link rel="canonical" href="{{ $canonicalUrl }}">

        <meta property="og:site_name" content="Cari Loker">
        <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $ogImage }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $ogImage }}">

        <title>{{ $metaTitle }}</title>

        <script type="application/ld+json">{!! json_encode($websiteJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @yield('structured_data')

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
    <body class="font-sans antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/90">
                <div class="section-container py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="min-h-[65vh]">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </body>
</html>
