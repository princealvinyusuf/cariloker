<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Welcome!') }}</h3>
                        <p>{{ __("You're logged in!") }}</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-2">
                            <a href="{{ route('about.edit') }}" class="block text-violet-600 hover:text-violet-700 font-medium">
                                {{ __('Edit About Page') }} →
                            </a>
                            <a href="{{ route('faq.edit') }}" class="block text-violet-600 hover:text-violet-700 font-medium">
                                {{ __('Edit FAQ Page') }} →
                            </a>
                            <a href="{{ route('jobs.index', ['list' => '1']) }}" class="block text-violet-600 hover:text-violet-700 font-medium">
                                {{ __('Browse Jobs') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
