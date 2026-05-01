import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Enable class-based dark mode
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Monochrome palette
                dark: '#0A0A0A',
                card: '#141414',
                borderdark: '#2A2A2A',
                light: '#F7F7F7',
                cardlight: '#FFFFFF',
                borderlight: '#E0E0E0',
            },
            boxShadow: {
                'minimal': '0 4px 20px rgba(0, 0, 0, 0.05)',
                'minimal-dark': '0 4px 20px rgba(0, 0, 0, 0.5)',
            },
            animation: {
                'fade-in': 'fadeIn 0.25s ease forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                }
            }
        },
    },

    plugins: [forms],
};
