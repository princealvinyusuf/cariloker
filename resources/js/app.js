import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize theme on load based on saved preference (defaults to light mode)
(() => {
    try {
        const saved = localStorage.getItem('theme_v2');
        // Default to light mode - only use dark if explicitly saved as 'dark'
        if (saved === 'dark') {
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
        localStorage.setItem('theme_v2', isDark ? 'dark' : 'light');
    } catch (_) {
        // ignore storage errors
    }
};

Alpine.start();
