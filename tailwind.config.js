import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                mono: ['Share Tech Mono', ...defaultTheme.fontFamily.mono],
                sans: ['Rajdhani', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    red:         '#DC2626',
                    'red-hot':   '#FF535B',
                    yellow:      '#F5B700',
                    'yellow-dim':'#FFD889',
                },
                ink: {
                    deep:        '#180A0A',
                    dark:        '#1E0F0F',
                    panel:       '#271717',
                    surface:     '#2C1B1B',
                },
                line: {
                    warm:        '#5B403F',
                    yellow:      '#F5B700',
                },
                copy: {
                    warm:        '#5B403F',
                    neutral:     '#A1A1AA',
                    soft:        '#FFB3B1',
                    ticker:      '#454745',
                },
            },
        },
    },
    plugins: [forms],
};
