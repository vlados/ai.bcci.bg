// Scroll-reveal + on-view chart animation.
//
// Only does anything when <html> has `motion-on` (set before paint, and only
// when the visitor hasn't requested reduced motion). If IntersectionObserver
// is unavailable, everything is revealed immediately so content is never stuck.

/**
 * Put `motion-on` back on <html>.
 *
 * The class is set by an inline script in the <head> so it lands before first
 * paint. wire:navigate then syncs <html>'s attributes from the incoming
 * document, which wipes it — and because every motion rule is gated on it, the
 * whole motion system (reveals, chart bars, ticker, dot pulse, hover lift)
 * silently stopped working after the first navigation.
 *
 * The class is deliberately JS-set rather than a bare media query: without JS
 * nothing can add `is-visible`, so content must stay visible instead of being
 * hidden by a reveal that will never fire.
 */
function armMotion() {
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    document.documentElement.classList.add('motion-on');
}

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

// Re-arm before the new HTML is painted. `onSwap` runs after the swap but
// before scripts load, which is exactly the window for restoring critical
// styling without a flash of un-animated content.
document.addEventListener('livewire:navigating', (e) => {
    e.detail?.onSwap?.(armMotion);
});

// ---------------------------------------------------------------------------
// Data story (/ai-adoption-2026): GSAP scroll animation, imported ONLY on that
// page so the rest of the site keeps its ~4KB JS budget.
//
// Contract, so this can never strand the page: every bar, line and number is
// authored in the HTML at its final, visible value. GSAP sets the from-states
// itself, so a failed dynamic import, a superseded navigation, or reduced
// motion all leave a finished static document behind — nothing stuck hidden.

let storyCtx = null; // active gsap.context, if any
let storyGen = 0; // bumped each setup; guards the async import against SPA nav

function decimalsOf(s) {
    const parts = String(s).split('.');
    return parts[1] ? parts[1].length : 0;
}

async function setupStory() {
    // Tear down first, before any guard: this runs on every boot() (including
    // wire:navigate), and the context outlives the DOM swap — leaving the story
    // for a plain page has to remove its ScrollTriggers or they leak and error.
    if (storyCtx) {
        storyCtx.revert();
        storyCtx = null;
    }

    const root = document.querySelector('[data-story]');
    if (!root || !document.documentElement.classList.contains('motion-on')) {
        return;
    }

    const gen = ++storyGen;
    let gsap, ScrollTrigger;
    try {
        ({ gsap } = await import('gsap'));
        ({ ScrollTrigger } = await import('gsap/ScrollTrigger'));
    } catch {
        return; // chunk unavailable (e.g. a deploy mid-session) — leave it static
    }

    // While the chunk loaded the visitor may have navigated again, or away: a
    // newer setup supersedes this one, or the root is no longer in the document.
    if (gen !== storyGen || !document.contains(root)) {
        return;
    }

    gsap.registerPlugin(ScrollTrigger);
    const locale = document.documentElement.lang || 'bg';

    storyCtx = gsap.context((self) => {
        const q = self.selector;

        // Fade-up reveals.
        q('[data-enter]').forEach((el) => {
            gsap.from(el, {
                opacity: 0,
                y: 22,
                duration: 0.7,
                ease: 'power2.out',
                scrollTrigger: { trigger: el, start: 'top 88%' },
            });
        });

        // Count-ups. Decimals follow whichever endpoint carries more, so a value
        // like 8.55 keeps both digits and 38 stays whole.
        q('[data-count]').forEach((el) => {
            const to = parseFloat(el.dataset.count);
            const from = el.dataset.from !== undefined ? parseFloat(el.dataset.from) : 0;
            const decimals = Math.max(decimalsOf(el.dataset.count), decimalsOf(el.dataset.from ?? '0'));
            const suffix = el.dataset.suffix || '';
            const fmt = (v) => {
                let s = v.toFixed(decimals);
                if (locale === 'bg') s = s.replace('.', ',');
                return s + suffix;
            };
            const obj = { v: from };
            el.textContent = fmt(from);
            gsap.to(obj, {
                v: to,
                duration: 1.4,
                ease: 'power2.out',
                onUpdate: () => (el.textContent = fmt(obj.v)),
                scrollTrigger: { trigger: el, start: 'top 90%' },
            });
        });

        // Vertical bars grow from the baseline, staggered across the group.
        const bars = root.querySelector('.st-bars');
        if (bars) {
            gsap.from(bars.querySelectorAll('[data-bar]'), {
                height: 0,
                duration: 0.9,
                ease: 'power3.out',
                stagger: 0.1,
                scrollTrigger: { trigger: bars, start: 'top 82%' },
            });
        }

        // Horizontal bars wipe in from the left (transform-origin set in CSS).
        root.querySelectorAll('.st-rank, .st-barriers').forEach((group) => {
            gsap.from(group.querySelectorAll('[data-bar-h]'), {
                scaleX: 0,
                duration: 0.8,
                ease: 'power3.out',
                stagger: 0.05,
                scrollTrigger: { trigger: group, start: 'top 82%' },
            });
        });

        // The one scrubbed moment: the two trend lines draw as the figure passes,
        // then the endpoint dots and values fade in.
        const fig = root.querySelector('[data-beat="line"] .st-figure');
        if (fig) {
            const lines = fig.querySelectorAll('[data-line]');
            const dots = fig.querySelectorAll('[data-dot]');
            lines.forEach((ln) => {
                const len = ln.getTotalLength();
                gsap.set(ln, { strokeDasharray: len, strokeDashoffset: len });
            });
            gsap.set(dots, { opacity: 0 });
            gsap
                .timeline({
                    scrollTrigger: { trigger: fig, start: 'top 72%', end: 'bottom 70%', scrub: 0.6 },
                })
                .to(lines, { strokeDashoffset: 0, ease: 'none', duration: 0.85 })
                .to(dots, { opacity: 1, duration: 0.15 }, '>-0.05');
        }
    }, root);

    // Trigger positions depend on metrics that settle after boot(): the Condensed
    // face shifts widths on load, and a wire:navigate view transition lands late.
    const refresh = () => gen === storyGen && ScrollTrigger.refresh();
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(refresh);
    }
    if (activeTransition) {
        activeTransition.finished.catch(() => {}).then(refresh);
    }
}

function boot() {
    armMotion(); // belt and braces, in case onSwap was unavailable
    setupReveals();
    setupStory();

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
