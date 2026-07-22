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

// ---------------------------------------------------------------------------
// View transitions across wire:navigate.
//
// wire:navigate is same-document — Livewire fetches the page and morphs the
// body — so the CSS `@view-transition` rule never fires for internal links; it
// only covers real document navigations. Bridging the two takes some care:
//
// `livewire:navigating` is NOT the hook. Alpine dispatches it and then swaps
// the DOM synchronously in the same task, while startViewTransition() captures
// the outgoing snapshot at the *next* rendering opportunity — by which point
// it would be photographing the new page. The result is a transition from the
// new page to itself.
//
// `livewire:navigate` is preventable (Livewire forwards preventDefault back to
// Alpine), so we cancel the navigation and re-issue it from inside the
// transition callback, where the outgoing snapshot is already safely taken.
// Livewire prefetches on mousedown, so the HTML is usually in flight before
// the click completes and the callback resolves quickly.

const prefersReducedMotion = () =>
    window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;

let activeTransition = null; // set while a navigation transition is running
let reNavigating = false; // guards our own Alpine.navigate() re-dispatch
let morphSource = null; // the card image that should carry across, if any

// Note which card was clicked, so exactly one image claims the shared
// transition name. Capture phase: this has to land before Livewire's handler.
document.addEventListener(
    'click',
    (e) => {
        const link = e.target.closest && e.target.closest('a[href]');
        morphSource = link ? link.closest('[data-morph-card]')?.querySelector('[data-morph]') : null;
    },
    true
);

/**
 * Guarantee exactly one element claims the shared name.
 *
 * A stale name is not a cosmetic problem: two elements answering to
 * `article-hero` makes the browser abort the whole transition. Names can go
 * stale when a navigation is abandoned mid-flight, and Livewire caches the
 * outgoing HTML for the back button — inline style and all — so a name left
 * behind can come back to life on a later visit.
 */
function releaseMorphNames() {
    document.querySelectorAll('[data-morph]').forEach((el) => {
        el.style.viewTransitionName = '';
    });
}

document.addEventListener('livewire:navigate', (e) => {
    if (reNavigating || !document.startViewTransition || prefersReducedMotion()) {
        return; // unsupported, or the visitor asked for stillness — navigate normally
    }
    if (e.detail?.history) {
        return; // back/forward: leave Livewire's history restoration alone
    }
    if (activeTransition) {
        // A second navigation while one is still animating (an impatient click,
        // or a link hit twice). Starting another transition here would abort
        // both with InvalidStateError — drop the old one and let this one
        // navigate plainly rather than fighting over the snapshot.
        activeTransition.skipTransition();
        return;
    }

    e.preventDefault();

    // Hold the element in this transition's own closure. Module state can be
    // reassigned by the next click before this transition finishes, which would
    // otherwise leave the first element's name behind.
    const morphEl = morphSource;
    releaseMorphNames();
    if (morphEl) {
        morphEl.style.viewTransitionName = 'article-hero';
    }

    // A slow response would leave the outgoing page frozen and uninteractive for
    // as long as the fetch takes, so give the swap a deadline. The timer is
    // cleared the moment the swap lands — it bounds the *fetch*, not the
    // animation, which is free to run as long as its CSS says.
    let bail;

    activeTransition = document.startViewTransition(() =>
        new Promise((resolve) => {
            document.addEventListener(
                'livewire:navigated',
                () => {
                    clearTimeout(bail);
                    resolve();
                },
                { once: true }
            );
            reNavigating = true;
            window.Alpine.navigate(String(e.detail.url));
            reNavigating = false;
        })
    );

    bail = setTimeout(() => activeTransition?.skipTransition(), 600);

    activeTransition.finished
        .catch(() => {}) // rejects when skipped; nothing to do
        .then(() => {
            clearTimeout(bail);
            if (morphEl) {
                morphEl.style.viewTransitionName = '';
            }
            activeTransition = null;
        });
});

/**
 * Reveal anything already on screen without animating it.
 *
 * The incoming page's `.reveal` elements start at opacity 0 and wait for the
 * IntersectionObserver. During a view transition the new snapshot can be taken
 * before the observer has fired, so the page would cross-fade to blank and then
 * pop in. Marking what's already in view as visible makes the snapshot honest.
 */
function revealWhatIsAlreadyInView() {
    document.querySelectorAll('.reveal:not(.is-visible), .chart:not(.is-visible)').forEach((el) => {
        const box = el.getBoundingClientRect();
        if (box.top < innerHeight && box.bottom > 0) {
            el.classList.add('is-visible');
        }
    });
}

function boot() {
    setupReveals();

    if (activeTransition) {
        revealWhatIsAlreadyInView();
        // Let the page transition land before the ball bounces — two motion
        // systems on the same header would otherwise talk over each other.
        activeTransition.finished.catch(() => {}).then(() => placeNavBall(true));
        return;
    }

    // Wait a frame so layout (and the swapped header) is settled.
    requestAnimationFrame(() => placeNavBall(true));
}

/**
 * Move focus to the new page after a wire:navigate swap.
 *
 * A real navigation resets focus to the top of the document; a same-document
 * swap does not, so a keyboard or screen-reader user is left with focus on a
 * link that no longer exists and has no idea the page changed. Focusing <main>
 * restores the behaviour the browser would have given us for free.
 *
 * Skipped on first paint — the browser has already done the right thing there,
 * and stealing focus on load would scroll past the header unprompted.
 */
let hasNavigated = false;

function restoreFocusAfterNavigation() {
    if (!hasNavigated) {
        hasNavigated = true;

        return;
    }

    const main = document.getElementById('main');
    if (main && document.activeElement !== main) {
        main.focus({ preventScroll: true });
    }
}

if (document.readyState !== 'loading') {
    boot();
} else {
    document.addEventListener('DOMContentLoaded', boot);
}
// Livewire's SPA navigation swaps the DOM without a full reload.
document.addEventListener('livewire:navigated', () => {
    boot();
    restoreFocusAfterNavigation();
});
window.addEventListener('resize', () => placeNavBall(false));
// Reposition once web fonts settle (condensed metrics can shift widths).
if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(() => placeNavBall(false));
}
