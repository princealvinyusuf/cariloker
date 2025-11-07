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

    const onScroll = () => {
        updateStickyState();
    };

    const onResize = () => {
        updatePlaceholder();
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);

    // initial run
    updateStickyState();

    // ensure placeholder stays updated when size changes
    const observer = new ResizeObserver(() => {
        updatePlaceholder();
    });
    observer.observe(container);
});


