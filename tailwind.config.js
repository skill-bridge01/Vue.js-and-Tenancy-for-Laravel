import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import tailwindAnimate from "tailwindcss-animated";

/* @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/protonemedia/laravel-splade/lib/**/*.vue",
        "./vendor/protonemedia/laravel-splade/resources/views/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],
    purge: ['./index.html', './resources/js/**/*.{vue,js,ts,jsx,tsx}'],
    darkMode: 'class', // or 'media' or 'class'

    theme: {
        extend: {
            fontFamily: {
                readex: [['Readex Pro']],
            },
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                primary: '#8b5cf6',
                success: '#10b981',
                danger: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6',
                light: '#6b7280',
                dark: '#111827',
                main: '#45A9CB',
                brandBackground: '#F1F7FE',
                brandGreen: '#4BC49A',
                brand_blue:'#0A80FE',
                brandRed: '#FF5757',
                brandBorder: '#ECF1F4',
            },
        },
    },
    variants: {
        extend: {},
    },

    plugins: [
        forms, 
        typography,
        tailwindAnimate,
        // require('tailwindcss-animate'),
    ],
};