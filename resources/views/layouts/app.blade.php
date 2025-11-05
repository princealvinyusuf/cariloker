<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Cari Loker: Temukan Impianmu</title>

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-+ZcN6Qw5HnqE4oQfQO6z6p8Jk8mXxw2qQn1GQzI8M6cyG3qk7cZc7iWq0uE6e4A0Lx5N1lYwzv4Q5V7+Xl+4oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="icon" type="image/svg+xml" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/svgs/solid/suitcase.svg">

        <!-- Scripts -->
        <script>
            // Apply theme early to avoid FOUC
            (function(){
                try {
                    var saved = localStorage.getItem('theme');
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (saved === 'dark' || (!saved && prefersDark)) {
                        document.documentElement.classList.add('dark');
                    }
                } catch (e) {}
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </body>
</html>
