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
                    DEFAULT: '#FF7B00', // main orange
                    500: '#FF7B00', // main orange
                    400: '#FF8D21',
                    300: '#FFA652',
                    200: '#FFB76B',
                    100: '#FFCD90',
                    50:  '#FFF4DF',
                    light: '#FFCD90',
                    dark: '#FF8D21',
                },
            },
        },
    },

    plugins: [forms],
};
