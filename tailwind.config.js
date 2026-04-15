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
                sans: ['Outfit', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Sora', 'Outfit', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 1px 2px rgba(2, 12, 27, 0.04), 0 8px 32px rgba(6, 78, 110, 0.08)',
                'card-hover':
                    '0 20px 50px rgba(2, 12, 27, 0.12), 0 8px 20px rgba(6, 182, 212, 0.12), 0 0 0 1px rgba(103, 232, 249, 0.15)',
                nav: '0 4px 24px rgba(2, 12, 27, 0.06)',
                glow: '0 0 0 1px rgba(255,255,255,0.12), 0 12px 40px rgba(6, 182, 212, 0.15), 0 4px 12px rgba(59, 130, 246, 0.1)',
                glass: '0 8px 32px rgba(2, 12, 27, 0.08), inset 0 1px 0 rgba(255,255,255,0.5)',
                'btn-primary': '0 4px 20px rgba(8, 145, 178, 0.45), 0 0 24px rgba(34, 211, 238, 0.25)',
                'glow-cyan': '0 0 40px rgba(34, 211, 238, 0.35)',
            },
            transitionDuration: {
                250: '250ms',
                400: '400ms',
            },
            keyframes: {
                'mesh-breathe': {
                    '0%, 100%': { opacity: '0.4', transform: 'scale(1) translate(0, 0)' },
                    '50%': { opacity: '0.65', transform: 'scale(1.04) translate(2%, -2%)' },
                },
                'blob-drift': {
                    '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                    '33%': { transform: 'translate(4%, -3%) scale(1.05)' },
                    '66%': { transform: 'translate(-3%, 4%) scale(0.98)' },
                },
                'blob-drift-slow': {
                    '0%, 100%': { transform: 'translate(0, 0) rotate(0deg)' },
                    '50%': { transform: 'translate(-5%, 5%) rotate(3deg)' },
                },
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(18px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                'modal-in': {
                    '0%': { opacity: '0', transform: 'translateY(12px) scale(0.98)' },
                    '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                },
                'glow-pulse': {
                    '0%, 100%': { opacity: '0.5' },
                    '50%': { opacity: '0.85' },
                },
                'diagonal-pan': {
                    '0%': { transform: 'translateX(0) translateY(0)' },
                    '100%': { transform: 'translateX(-4%) translateY(-2%)' },
                },
            },
            animation: {
                'mesh-breathe': 'mesh-breathe 16s ease-in-out infinite',
                'mesh-breathe-delayed': 'mesh-breathe 20s ease-in-out infinite 6s',
                'blob-drift': 'blob-drift 22s ease-in-out infinite',
                'blob-drift-slow': 'blob-drift-slow 28s ease-in-out infinite',
                'fade-up': 'fade-up 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                shimmer: 'shimmer 2.8s ease-in-out infinite',
                'modal-in': 'modal-in 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'glow-pulse': 'glow-pulse 4s ease-in-out infinite',
            },
            backgroundSize: {
                mesh: '200% 200%',
            },
        },
    },

    plugins: [forms],
};
