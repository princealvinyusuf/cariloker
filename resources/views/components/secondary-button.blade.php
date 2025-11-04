<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-primary text-primary rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-primary-100 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-primary-50 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
