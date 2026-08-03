import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                clay: {
                    bg: '#EEF0FA',
                    surface: '#F3F5FC',
                    primary: '#8C7CF0',
                    'primary-dark': '#7566DE',
                    peach: '#FFB199',
                    mint: '#6FE0C4',
                    sky: '#87C9FB',
                    yolk: '#FFD37A',
                    text: '#3F3A5C',
                    muted: '#8A85AC',
                },
            },
            boxShadow: {
                clay: '10px 10px 22px rgba(163,177,209,0.55), -10px -10px 22px rgba(255,255,255,0.85)',
                'clay-sm': '5px 5px 12px rgba(163,177,209,0.5), -5px -5px 12px rgba(255,255,255,0.8)',
                'clay-inset': 'inset 4px 4px 10px rgba(163,177,209,0.45), inset -4px -4px 10px rgba(255,255,255,0.75)',
                'clay-pressed': 'inset 3px 3px 8px rgba(120,110,180,0.35), inset -3px -3px 8px rgba(255,255,255,0.6)',
            },
            borderRadius: {
                clay: '1.75rem',
            },
        },
    },

    plugins: [forms],
};
