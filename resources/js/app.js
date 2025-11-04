import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize theme on load based on saved preference or system
(() => {
    try {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch (_) {
        // no-op if localStorage is unavailable
    }
})();

// Expose a global toggle for Blade templates
window.toggleTheme = () => {
    const isDark = document.documentElement.classList.toggle('dark');
    try {
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    } catch (_) {
        // ignore storage errors
    }
};

Alpine.start();
