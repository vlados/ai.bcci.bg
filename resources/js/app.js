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

// ---------------------------------------------------------------------------
// Nav "ball": a red dot riding the top of the desktop nav that bounces to the
// new active item on page change. The ball is @persist-ed across wire:navigate,
// so `lastX` (module scope, set once) carries the position to animate FROM.
// Honors reduced motion (snaps instead of bouncing).

let navBallX = null;

function activeNavItem() {
    const nav = document.querySelector('[data-desknav]');
    if (!nav || getComputedStyle(nav).display === 'none') {
        return null; // hidden on mobile
    }
    return nav.querySelector('a[aria-current="page"]');
}

function placeNavBall(animate) {
    const ball = document.querySelector('[data-navball]');
    if (!ball) {
        return;
    }

    const item = activeNavItem();
    const header = ball.offsetParent;
    if (!item || !header) {
        ball.style.opacity = '0';
        return;
    }

    const hb = header.getBoundingClientRect();
    const ib = item.getBoundingClientRect();
    const toX = Math.round(ib.left + ib.width / 2 - hb.left - ball.offsetWidth / 2);

    ball.style.opacity = '1';
    ball.style.transform = `translateX(${toX}px)`; // resting state

    const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    const shouldAnimate = animate && navBallX !== null && navBallX !== toX && !reduce;

    if (shouldAnimate && typeof ball.animate === 'function') {
        const fromX = navBallX;
        const dx = toX - fromX;
        const at = (f) => `${fromX + dx * f}px`;
        const up = 'cubic-bezier(.33,.66,.4,1)';   // decelerate toward apex
        const down = 'cubic-bezier(.6,.04,.98,.34)'; // accelerate toward ground

        ball.animate(
            [
                { transform: `translateX(${at(0)}) translateY(0) scale(1,1)`, offset: 0, easing: up },
                { transform: `translateX(${at(0.28)}) translateY(-26px) scale(0.84,1.2)`, offset: 0.16, easing: down },
                { transform: `translateX(${at(0.5)}) translateY(0) scale(1.32,0.74)`, offset: 0.32, easing: up },
                { transform: `translateX(${at(0.68)}) translateY(-15px) scale(0.9,1.12)`, offset: 0.48, easing: down },
                { transform: `translateX(${at(0.8)}) translateY(0) scale(1.2,0.82)`, offset: 0.62, easing: up },
                { transform: `translateX(${at(0.9)}) translateY(-7px) scale(0.95,1.06)`, offset: 0.74, easing: down },
                { transform: `translateX(${at(0.96)}) translateY(0) scale(1.1,0.9)`, offset: 0.84, easing: up },
                { transform: `translateX(${at(0.99)}) translateY(-3px) scale(1,1)`, offset: 0.93, easing: down },
                { transform: `translateX(${toX}px) translateY(0) scale(1,1)`, offset: 1 },
            ],
            { duration: 780, easing: 'linear' }
        );
    }

    navBallX = toX;
}

function boot() {
    setupReveals();
    // Wait a frame so layout (and the swapped header) is settled.
    requestAnimationFrame(() => placeNavBall(true));
}

if (document.readyState !== 'loading') {
    boot();
} else {
    document.addEventListener('DOMContentLoaded', boot);
}
// Livewire's SPA navigation swaps the DOM without a full reload.
document.addEventListener('livewire:navigated', boot);
window.addEventListener('resize', () => placeNavBall(false));
// Reposition once web fonts settle (condensed metrics can shift widths).
if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(() => placeNavBall(false));
}
