@section('meta_title', __('SleepWell Onboarding - Cari Loker'))
@section('meta_robots', 'noindex,nofollow')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900 dark:text-white">
            {{ __('SleepWell Onboarding Screens') }}
        </h2>
    </x-slot>

    <div class="section-container py-10">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.sleepwell.dashboard') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-primary-300 hover:text-primary-700 dark:border-slate-700 dark:text-slate-200">
                ← {{ __('Back to SleepWell Admin') }}
            </a>
            <a href="{{ route('admin.sleepwell.onboarding.create') }}"
               class="rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                {{ __('Add Onboarding Screen') }}
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="surface-card overflow-x-auto p-4">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead>
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="px-3 py-2">{{ __('Order') }}</th>
                        <th class="px-3 py-2">{{ __('Step Key') }}</th>
                        <th class="px-3 py-2">{{ __('Type') }}</th>
                        <th class="px-3 py-2">{{ __('Title') }}</th>
                        <th class="px-3 py-2">{{ __('Status') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($screens as $screen)
                        <tr>
                            <td class="px-3 py-3 text-slate-700 dark:text-slate-200">{{ $screen->sort_order }}</td>
                            <td class="px-3 py-3 font-medium text-slate-900 dark:text-white">{{ $screen->step_key }}</td>
                            <td class="px-3 py-3 text-slate-700 dark:text-slate-200">{{ $screen->screen_type }}</td>
                            <td class="px-3 py-3 text-slate-700 dark:text-slate-200">{{ $screen->title }}</td>
                            <td class="px-3 py-3">
                                @if($screen->is_active)
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">{{ __('Active') }}</span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-2 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.sleepwell.onboarding.edit', $screen) }}"
                                       class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-600 dark:text-slate-200">
                                        {{ __('Edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.sleepwell.onboarding.destroy', $screen) }}" onsubmit="return confirm('Delete this onboarding screen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-lg border border-rose-300 px-3 py-1.5 text-xs font-medium text-rose-700 dark:border-rose-600 dark:text-rose-300">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-slate-600 dark:text-slate-300">
                                {{ __('No onboarding screens found yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $screens->links() }}
        </div>
    </div>
</x-app-layout>
