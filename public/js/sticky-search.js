document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('job-search-bar');
    if (!container) {
        return;
    }

    const placeholder = document.createElement('div');
    const wrapper = container.querySelector('.search-bar-wrapper');
    const focusableSelectors = 'input, select, textarea, button, a';
    let isSticky = false;
    let lastWidth = null;

    const updatePlaceholder = () => {
        const rect = wrapper.getBoundingClientRect();
        placeholder.style.height = `${rect.height}px`;
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
        document.body.classList.add('has-sticky-search');
        updatePlaceholder();
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
        document.body.classList.remove('has-sticky-search');
    };

    const updateStickyState = () => {
        const rect = container.getBoundingClientRect();
        const shouldStick = rect.top <= 16;
        if (shouldStick) {
            applySticky();
        } else {
            removeSticky();
        }
    };

    const updateWidth = () => {
        if (!isSticky) return;
        const computedWidth = container.offsetWidth;
        if (computedWidth !== lastWidth) {
            wrapper.style.width = `${computedWidth}px`;
            lastWidth = computedWidth;
        }
    };

    const onScroll = () => {
        updateStickyState();
        updateWidth();
    };

    const onResize = () => {
        updateWidth();
        updatePlaceholder();
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);

    // initial run
    updateStickyState();

    // ensure width matches container when sticky
    const observer = new ResizeObserver(() => {
        updateWidth();
        updatePlaceholder();
    });
    observer.observe(container);
});


