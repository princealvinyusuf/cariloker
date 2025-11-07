document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('job-search-bar');
    if (!container) {
        return;
    }

    const placeholder = document.createElement('div');
    const wrapper = container.querySelector('.search-bar-wrapper');
    const focusableSelectors = 'input, select, textarea, button, a';
    let isSticky = false;

    const updatePlaceholder = () => {
        const rect = wrapper.getBoundingClientRect();
        placeholder.style.height = `${rect.height}px`;
        placeholder.style.width = `${rect.width}px`;
        const computedStyle = window.getComputedStyle(container);
        placeholder.style.marginTop = computedStyle.marginTop;
        placeholder.style.marginBottom = computedStyle.marginBottom;
    };

    const getTopOffset = () => {
        const nav = document.querySelector('nav');
        if (nav) {
            return nav.getBoundingClientRect().height + 12;
        }
        return 16;
    };

    const applyStickyStyles = () => {
        if (!isSticky) return;
        const rect = placeholder.getBoundingClientRect();
        wrapper.style.position = 'fixed';
        wrapper.style.top = `${getTopOffset()}px`;
        wrapper.style.left = `${rect.left}px`;
        wrapper.style.width = `${rect.width}px`;
        wrapper.style.zIndex = '40';
    };

    const applySticky = () => {
        if (isSticky) return;
        isSticky = true;
        container.parentNode.insertBefore(placeholder, container);
        wrapper.classList.add('sticky-search-active');
        wrapper.setAttribute('role', 'region');
        wrapper.setAttribute('aria-label', 'Job search form');
        wrapper.querySelectorAll(focusableSelectors).forEach(el => {
            el.classList.add('focus-visible-outline');
        });
        updatePlaceholder();
        applyStickyStyles();
    };

    const removeSticky = () => {
        if (!isSticky) return;
        isSticky = false;
        if (placeholder.parentNode) {
            placeholder.parentNode.removeChild(placeholder);
        }
        wrapper.classList.remove('sticky-search-active');
        wrapper.removeAttribute('role');
        wrapper.removeAttribute('aria-label');
        wrapper.querySelectorAll(focusableSelectors).forEach(el => {
            el.classList.remove('focus-visible-outline');
        });
        wrapper.style.position = '';
        wrapper.style.top = '';
        wrapper.style.left = '';
        wrapper.style.width = '';
        wrapper.style.zIndex = '';
    };

    const updateStickyState = () => {
        const rect = container.getBoundingClientRect();
        const shouldStick = rect.top <= 16;
        if (shouldStick) {
            applySticky();
            applyStickyStyles();
        } else {
            removeSticky();
        }
    };

    const onScroll = () => {
        updateStickyState();
        applyStickyStyles();
    };

    const onResize = () => {
        updatePlaceholder();
        applyStickyStyles();
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);

    // initial run
    updateStickyState();

    // ensure placeholder stays updated when size changes
    const observer = new ResizeObserver(() => {
        updatePlaceholder();
        applyStickyStyles();
    });
    observer.observe(container);
});


