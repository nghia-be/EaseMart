const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        '.public/assets/js/main.js',
        './assets/js/projects.js',
        './assets/js/tickets.js',
        './assets/js/app.js',
        './assets/js/map.js'
    ],
    safelist: [
        {
            pattern:
                /^panel|btn-|bg-|text-|label-|badge-|bg-|dropdown|nav-|nav-tabs|pagination-|fc-|alert-.*/,
        },
    ],
    prefix: 'tw-',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                sm: '0.8rem',
                base: '0.9rem',
                normal: '0.84375rem',
            },
            animation: {
                'spin-slow': 'spin 3s linear infinite',
            },
            colors: {
                transparent: 'transparent',
                inherit: colors.inherit,
                current: 'currentColor',

                black: colors.black,
                white: colors.white,

                neutral: colors.slate,
                danger: colors.red,
                warning: colors.yellow,
                success: colors.green,
                info: colors.sky,
                primary: colors.blue,
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
    corePlugins: {
        preflight: false,
    },
};
