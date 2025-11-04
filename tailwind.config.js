import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#82D1F1',   // Baby Blue (main)
                    500: '#82D1F1',      // Baby Blue (main)
                    400: '#ACE7F8',      // Fresh Air (light)
                    300: '#CBF3F9',      // Water (lighter)
                    light: '#ACE7F8',    // Fresh Air
                    lighter: '#CBF3F9',  // Water
                    dark: '#509fcf',     // Custom, deeper blue if needed
                },
            },
        },
    },

    plugins: [forms],
};
