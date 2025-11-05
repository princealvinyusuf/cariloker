<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('jobs.index') }}" class="flex items-center gap-2">
                        <span class="text-2xl font-bold">
                            <span class="text-violet-600">Cari</span><span class="text-orange-500"> Loker</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:ml-10 sm:flex">
                    @php
                        $isJobsListing = request()->routeIs('jobs.index') && (request()->has('list') || request()->hasAny(['q', 'location', 'type', 'category', 'remote', 'min_salary', 'experience', 'salary_range', 'date_posted', 'work_arrangement', 'sort', 'page']));
                        $isHomePage = request()->routeIs('jobs.index') && !$isJobsListing;
                    @endphp
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $isHomePage ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }} transition-colors">
                        {{ __('Home') }}
                    </a>
                    <a href="{{ route('jobs.index', ['list' => '1']) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $isJobsListing ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }} transition-colors">
                        {{ __('Jobs') }}
                    </a>
                    <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('companies.index') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }} transition-colors">
                        {{ __('Companies') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('blog.*') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }} transition-colors">
                        {{ __('Blog') }}
                    </a>
                    <a href="{{ route('about') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }} transition-colors">
                        {{ __('About') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Theme Toggle Button (Visible to all users) -->
                <button type="button" onclick="window.toggleTheme()" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" aria-label="Toggle theme">
                    <!-- Moon icon for dark mode (shown when in light mode) -->
                    <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    <!-- Sun icon for light mode (shown when in dark mode) -->
                    <svg class="h-5 w-5 hidden dark:inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 18a6 6 0 100-12 6 6 0 000 12zm0 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm0-20a1 1 0 01-1-1V0a1 1 0 112 0v1a1 1 0 01-1 1zm-8 9a1 1 0 011-1H6a1 1 0 110 2H5a1 1 0 01-1-1zm14 0a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM4.22 18.36a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM17.66 4.22a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM4.22 5.64a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0zM17.66 19.78a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0z"/>
                    </svg>
                </button>
                
                <!-- Language Switcher (Visible to all users) -->
                <x-dropdown align="right" width="36">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center px-3 h-9 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="text-sm font-medium uppercase">{{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}</span>
                            <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('locale.switch', 'id')">{{ __('ID - Indonesia') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('locale.switch', 'en')">{{ __('EN - English') }}</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                
                @auth
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-white bg-violet-600 hover:bg-violet-700 dark:bg-violet-500 dark:hover:bg-violet-600 transition-colors">
                        {{ __('Upload Resume') }}
                    </a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div>{{ auth()->user()?->name }}</div>
                                <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
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
                @endauth
                
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        {{ __('Log in') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-white bg-violet-600 hover:bg-violet-700 dark:bg-violet-500 dark:hover:bg-violet-600 transition-colors">
                        {{ __('Register') }}
                    </a>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @php
                $isJobsListing = request()->routeIs('jobs.index') && (request()->has('list') || request()->hasAny(['q', 'location', 'type', 'category', 'remote', 'min_salary', 'experience', 'salary_range', 'date_posted', 'work_arrangement', 'sort', 'page']));
                $isHomePage = request()->routeIs('jobs.index') && !$isJobsListing;
            @endphp
            <a href="{{ route('jobs.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ $isHomePage ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }}">
                {{ __('Home') }}
            </a>
            <a href="{{ route('jobs.index', ['list' => '1']) }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ $isJobsListing ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }}">
                {{ __('Jobs') }}
            </a>
            <a href="{{ route('companies.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('companies.index') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }}">
                {{ __('Companies') }}
            </a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('blog.*') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }}">
                {{ __('Blog') }}
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('about') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30' : 'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30' }}">
                {{ __('About') }}
            </a>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 px-4 space-y-3">
            <!-- Theme Toggle Button (Mobile) -->
            <button type="button" onclick="window.toggleTheme()" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-900/30 transition-colors">
                <span>{{ __('Toggle Theme') }}</span>
                <div class="flex items-center">
                    <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    <svg class="h-5 w-5 hidden dark:inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 18a6 6 0 100-12 6 6 0 000 12zm0 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm0-20a1 1 0 01-1-1V0a1 1 0 112 0v1a1 1 0 01-1 1zm-8 9a1 1 0 011-1H6a1 1 0 110 2H5a1 1 0 01-1-1zm14 0a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM4.22 18.36a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM17.66 4.22a1 1 0 011.42 0l.71.71a1 1 0 11-1.42 1.42l-.71-.71a1 1 0 010-1.42zM4.22 5.64a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0zM17.66 19.78a1 1 0 010-1.42l.71-.71a1 1 0 111.42 1.42l-.71.71a1 1 0 01-1.42 0z"/>
                    </svg>
                </div>
            </button>
            
            <!-- Language Switcher (Mobile) -->
            <div class="px-3 py-2">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Language') }}</div>
                <div class="flex gap-2">
                    <a href="{{ route('locale.switch', 'id') }}" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium text-center {{ app()->getLocale() === 'id' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
                        {{ __('ID') }}
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium text-center {{ app()->getLocale() === 'en' ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
                        {{ __('EN') }}
                    </a>
                </div>
            </div>
            
            @auth
                <div class="px-3">
                    <div class="font-medium text-base text-gray-900 dark:text-gray-100">{{ auth()->user()?->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()?->email }}</div>
                </div>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30">
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30">
                        {{ __('Log Out') }}
                    </button>
                </form>
            @endauth
            
            @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30">
                    {{ __('Log in') }}
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30">
                    {{ __('Register') }}
                </a>
            @endguest
        </div>
    </div>
</nav>
