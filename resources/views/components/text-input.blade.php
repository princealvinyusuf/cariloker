@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-primary-100 dark:border-primary-100 dark:bg-primary-50 dark:text-primary-500 focus:border-primary focus:ring-primary dark:focus:border-primary-400 dark:focus:ring-primary-400 rounded-md shadow-sm']) }}>
