<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl bg-primary-600 px-4 py-2.5 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
