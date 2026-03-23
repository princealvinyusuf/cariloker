<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/90">
    @php
        $isJobsListing = request()->routeIs('jobs.index');
        $isBeranda = request()->routeIs('home', 'beranda');
        $isCvReviewer = request()->routeIs('cv.reviewer');
    @endphp
    <div class="section-container">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        <span class="text-primary-600">Cari</span> <span>Loker</span>
                    </span>
                </a>

                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <a href="{{ route('home') }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ $isBeranda ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('Beranda') }}
                    </a>
                    <a href="{{ route('jobs.index', ['list' => '1']) }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ $isJobsListing ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('Jobs') }}
                    </a>
                    <a href="{{ route('companies.index') }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('companies.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('Companies') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('blog.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('Blog') }}
                    </a>
                    <a href="{{ route('about') }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('About') }}
                    </a>
                    <a href="{{ route('cv.reviewer') }}" class="rounded-xl px-3 py-2 text-sm font-medium {{ $isCvReviewer ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        {{ __('Bedan CV Gratis') }}
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <form method="GET" action="{{ route('jobs.index') }}" class="hidden lg:block">
                    <input type="hidden" name="list" value="1">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="{{ __('Cari lowongan...') }}"
                            class="w-56 rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                    </div>
                </form>
                <a href="{{ route('jobs.index', ['list' => 1]) }}" class="btn-secondary !px-4 !py-2">{{ __('Find Jobs') }}</a>
                <button type="button" onclick="window.toggleTheme()" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300" aria-label="Toggle theme">
                    <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    <svg class="hidden h-5 w-5 dark:inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 100-12 6 6 0 000 12zm0 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm0-20a1 1 0 01-1-1V0a1 1 0 112 0v1a1 1 0 01-1 1zm-8 9a1 1 0 011-1H6a1 1 0 110 2H5a1 1 0 01-1-1zm14 0a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM4.22 18.36a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM17.66 4.22a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM4.22 5.64a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0zM17.66 19.78a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0z"/></svg>
                </button>

                <x-dropdown align="right" width="36">
                    <x-slot name="trigger">
                        <button class="inline-flex h-9 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium uppercase text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            <span>{{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}</span>
                            <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('locale.switch', 'id')">{{ __('ID - Indonesia') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('locale.switch', 'en')">{{ __('EN - English') }}</x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                <span>{{ auth()->user()?->name }}</span>
                                <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">{{ __('Dashboard') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="btn-primary !px-4 !py-2">{{ __('Sign In') }}</a>
                @endauth
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white px-4 pb-4 pt-2 dark:border-slate-800 dark:bg-slate-950 sm:hidden">
        <form method="GET" action="{{ route('jobs.index') }}" class="mb-3">
            <input type="hidden" name="list" value="1">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ __('Cari lowongan...') }}"
                    class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
            </div>
        </form>

        <div class="space-y-1">
            <a href="{{ route('home') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('Beranda') }}</a>
            <a href="{{ route('jobs.index', ['list' => '1']) }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('Jobs') }}</a>
            <a href="{{ route('companies.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('Companies') }}</a>
            <a href="{{ route('blog.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('Blog') }}</a>
            <a href="{{ route('about') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('About') }}</a>
            <a href="{{ route('cv.reviewer') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('Bedan CV Gratis') }}</a>
        </div>

        <div class="mt-4 space-y-2 border-t border-slate-200 pt-4 dark:border-slate-800">
            <button type="button" onclick="window.toggleTheme()" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ __('Toggle Theme') }}</button>
            @auth
                <a href="{{ route('dashboard') }}" class="block rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ __('Dashboard') }}</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-left text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ __('Log Out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-primary w-full">{{ __('Sign In') }}</a>
            @endauth
        </div>
    </div>
</nav>
