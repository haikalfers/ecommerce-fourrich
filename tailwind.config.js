import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary — Orange
                primary: {
                    50:  '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                    950: '#431407',
                },
                // Dark — Charcoal sidebar/navbar
                dark: {
                    50:  '#f0f0f5',
                    100: '#d9d9e8',
                    200: '#b3b3d1',
                    300: '#8888b5',
                    400: '#5f5f97',
                    500: '#3a3a6e',
                    600: '#2d2d58',
                    700: '#252542',
                    800: '#1e1e2d',
                    900: '#14141f',
                    950: '#0a0a11',
                },
            },
        },
    },

    plugins: [forms],
};
