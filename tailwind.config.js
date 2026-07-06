import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#cb0c9f',
                    light: '#e293d3',
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                accent: {
                    DEFAULT: '#d97706',
                    light: '#f59e0b',
                },
                secondary: '#8392ab',
                info: '#17c1e8',
                success: '#82d616',
                warning: '#fbcf33',
                danger: '#ea0606',
                surface: '#ffffff',
                background: '#f8f9fa',
            },
            boxShadow: {
                'soft': '0 20px 27px 0 rgba(0,0,0,0.05)',
                'soft-sm': '0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)',
            },
        },
    },

    plugins: [forms],
};
