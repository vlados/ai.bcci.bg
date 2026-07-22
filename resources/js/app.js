// Scroll-reveal + on-view chart animation.
//
// Only does anything when <html> has `motion-on` (set before paint, and only
// when the visitor hasn't requested reduced motion). If IntersectionObserver
// is unavailable, everything is revealed immediately so content is never stuck.

function setupReveals() {
    if (!document.documentElement.classList.contains('motion-on')) {
        return;
    }

    const targets = document.querySelectorAll('.reveal:not(.is-visible), .chart:not(.is-visible)');
    if (!targets.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
    );

    targets.forEach((el) => observer.observe(el));
}

if (document.readyState !== 'loading') {
    setupReveals();
} else {
    document.addEventListener('DOMContentLoaded', setupReveals);
}
// Livewire's SPA navigation swaps the DOM without a full reload.
document.addEventListener('livewire:navigated', setupReveals);
