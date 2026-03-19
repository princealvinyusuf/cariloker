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
                    50: '#eef8ff',
                    100: '#d7eeff',
                    200: '#b3dcff',
                    300: '#82c4ff',
                    400: '#489fff',
                    500: '#1f7bff',
                    600: '#145ef0',
                    700: '#134bcd',
                    800: '#153fa5',
                    900: '#183a82',
                    DEFAULT: '#1f7bff',
                    light: '#d7eeff',
                    lighter: '#eef8ff',
                    dark: '#145ef0',
                },
                accent: {
                    50: '#ecfeff',
                    100: '#cff9ff',
                    200: '#a7f1ff',
                    300: '#67e5ff',
                    400: '#22d3ee',
                    500: '#06b6d4',
                    600: '#0891b2',
                    700: '#0e7490',
                    800: '#155e75',
                    900: '#164e63',
                    DEFAULT: '#06b6d4',
                },
                neutral: {
                    950: '#0a0f1f',
                },
            },
            boxShadow: {
                soft: '0 10px 30px rgba(15, 23, 42, 0.08)',
                glow: '0 18px 45px rgba(31, 123, 255, 0.18)',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
        },
    },

    plugins: [forms],
};
