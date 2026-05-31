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
                coal: {
                    black:       '#0a0a0a',   // tiefster Hintergrund (Sidebar, Topbar)
                    deep:        '#111111',   // Overlays, Modals
                    panel:       '#141414',   // Cards, Dropdowns
                    surface:     '#1a1a1a',   // Hover-States, Image-Placeholder
                    line:        '#2a2a2a',   // Borders, Trennlinien
                    'line-soft': '#1e1e1e',   // subtile Borders
                },
                line: {
                    warm:        '#5B403F',
                    yellow:      '#F5B700',
                },
                copy: {
                    warm:        '#888888',
                    neutral:     '#A1A1AA',
                    soft:        '#e8e8e8',
                    ticker:      '#999999',
                },
            },
        },
    },
    plugins: [forms],
};
