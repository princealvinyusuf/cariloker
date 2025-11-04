<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-primary-400 focus:bg-primary-400 active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-primary-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
