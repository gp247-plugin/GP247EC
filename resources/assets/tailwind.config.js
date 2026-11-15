/*
 * Tailwind config for the GP247-EC template (key GP247EC) — compiled ahead of
 * time with the Tailwind v3 CLI (no runtime/JIT on the storefront).
 *
 * GP247-EC is SELF-CONTAINED: every view it renders (front + shop screens,
 * account, auth, Livewire, emails, components) lives in this folder, so the
 * build scans this folder instead of the gp247/front and gp247/shop packages.
 * Content globs are relative to the CWD: run the build from the host Laravel
 * project root (see readme.md "Build CSS").
 *
 * Design tokens (brand / accent / ink) keep the same NAMES as GP247Front on
 * purpose: plugin storefront views render inside this template's layout and
 * only use those names plus the component classes in css/app.css.
 */

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // The template itself — every view GP247-EC renders lives here.
        'app/GP247/Templates/GP247EC/**/*.blade.php',
        'app/GP247/Templates/GP247EC/resources/assets/js/**/*.js',
        // WHY: plugin storefront views render inside this layout; their utility
        // classes must exist in this bundle or they silently lose styling.
        'app/GP247/Plugins/*/Views/**/*.blade.php',
        // WHY: pagination is rendered from vendor views (Livewire WithPagination
        // for the reactive product grid, Laravel's default for ->links); without
        // scanning them their `sm:` classes are missing and both layouts show.
        'vendor/livewire/livewire/src/Features/SupportPagination/views/*.blade.php',
        'vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // Orange — the template's main tone: header, primary actions, highlights.
                // Orange. 500 / 550 = surfaces (header, bars, gradients); 600+ = text and
                // buttons with white text — 600 #c2560f reaches 4.5:1 on white because
                // plugin views use text-brand-600 / bg-brand-600 as their accent.
                brand: {
                    50: '#fff8f1',
                    100: '#ffeedd',
                    200: '#ffd9b5',
                    300: '#ffbf88',
                    400: '#ffa35a',
                    500: '#fb8a3c',
                    550: '#ea6f1f',
                    600: '#c2560f',
                    700: '#9a4410',
                    800: '#7a3612',
                    900: '#5c290d',
                },
                // Crimson — discounts, sale ribbons, urgency.
                accent: {
                    50: '#fff1f2',
                    100: '#ffe4e6',
                    500: '#e11d48',
                    600: '#be123c',
                    700: '#9f1239',
                },
                // Neutral near-black scale — header, footer, text, surfaces.
                ink: {
                    50: '#fafafa',
                    100: '#f4f4f5',
                    200: '#e4e4e7',
                    300: '#d4d4d8',
                    400: '#a1a1aa',
                    500: '#71717a',
                    600: '#52525b',
                    700: '#3f3f46',
                    800: '#27272a',
                    900: '#18181b',
                    950: '#0b0b0c',
                },
            },
            fontFamily: {
                sans: ['Roboto', 'Helvetica Neue', 'Arial', 'Segoe UI', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                arabic: ['Cairo', 'Segoe UI', 'Tahoma', 'sans-serif'],
            },
            boxShadow: {
                soft: '0 2px 12px -4px rgb(0 0 0 / 0.12)',
                lift: '0 8px 24px -8px rgb(0 0 0 / 0.25)',
            },
            borderRadius: {
                xl: '0.5rem',
                '2xl': '0.75rem',
            },
        },
    },
    plugins: [],
};
