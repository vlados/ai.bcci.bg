{{--
    OpenPanel (openpanel.dev) — privacy-friendly, cookieless web analytics.

    Loads ONLY on the canonical production host (same rule as the SEO layer's
    noindex, so a dev box / preview / bare IP never feeds the real project).
    Override with OPENPANEL_ENABLED to force it on locally or off in production.

    How the events map to this Livewire SPA:
      • screen_view — automatic. trackScreenViews patches history.pushState,
        which is exactly what wire:navigate uses, so every internal navigation
        is counted (path + title captured after Livewire's swap). Segmented by
        `locale` via the global property below.
      • link_out    — automatic. Every outbound <a> (survey questionnaire,
        newsletter, article shares…) fires this on click.
      • custom      — declared inline with `data-track` on <a>/<button>; any
        other data-* attribute rides along as a property (camelCased). Plus two
        JS-driven ones the markup can't express: contact_submit (server-
        confirmed) and story_complete (scrolled to the end of the data story).
--}}
@php
    $op = config('services.openpanel');
    $clientId = $op['client_id'] ?? null;

    // Same gate as \App\Support\Seo::onProductionHost(): only the one canonical
    // host is real; a blank host disables the check (explicit opt-out).
    $prodHost = config('site.seo.production_host');
    $onProdHost = blank($prodHost) || request()->getHost() === $prodHost;
    $enabled = filled($clientId) && ($op['enabled'] ?? $onProdHost);

    // Preconnect to the beacon and script origins so the first hit is quick.
    $origin = fn ($url) => $url ? implode('', array_filter([
        parse_url($url, PHP_URL_SCHEME) ? parse_url($url, PHP_URL_SCHEME).'://' : null,
        parse_url($url, PHP_URL_HOST),
    ])) : null;
    $apiOrigin = $origin($op['api_url'] ?? null);
    $scriptOrigin = $origin($op['script_url'] ?? null);
@endphp

@if ($enabled)
    @if ($apiOrigin)
        <link rel="preconnect" href="{{ $apiOrigin }}" crossorigin>
    @endif
    @if ($scriptOrigin && $scriptOrigin !== $apiOrigin)
        <link rel="preconnect" href="{{ $scriptOrigin }}" crossorigin>
    @endif

    {{-- Queue proxy: window.op(...) buffers calls until op1.js replays them by
         method name — so `init` MUST be the first call. --}}
    <script>
        window.op = window.op || function () { var n = []; return new Proxy(function () { arguments.length && n.push([].slice.call(arguments)) }, { get: function (t, r) { return "q" === r ? n : function () { n.push([r].concat([].slice.call(arguments))) } }, has: function (t, r) { return "q" === r } }) }();
        window.op('init', {
            apiUrl: @json($op['api_url']),
            clientId: @json($clientId),
            trackScreenViews: true,   // patches history.pushState → catches wire:navigate
            trackOutgoingLinks: true, // every external <a> → `link_out`
            trackAttributes: true,    // <a|button data-track="…"> → custom event
            // sessionReplay: { enabled: true },
        });
        // Segment every event by UI language. `lang` is on <html> before this
        // runs; it's refreshed on each wire:navigate below so a language switch
        // is reflected immediately.
        window.op('setGlobalProperties', { locale: document.documentElement.lang || @json(app()->getLocale()) });
    </script>
    <script src="{{ $op['script_url'] }}" defer async></script>

    {{-- SPA glue: the few things auto-tracking can't do on its own. --}}
    <script>
        (function () {
            // Call op1.js directly (spread), never via .apply: once op1.js loads
            // it swaps window.op for a Proxy whose get-trap makes `.apply`
            // undefined, so window.op.apply(...) throws and the event is lost.
            var op = function () { if (window.op) window.op(...arguments); };

            // Fire once when the closing section of the data story scrolls into
            // view. Enhancement only (no IntersectionObserver → no event, never
            // a broken page); idempotent via a per-element flag so re-boots and
            // navigations can't double-count.
            function watchStoryEnd() {
                if (!('IntersectionObserver' in window)) return;
                var end = document.querySelector('[data-story] .st-close');
                if (!end || end.dataset.opSeen) return;
                end.dataset.opSeen = '1';
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) { io.disconnect(); op('track', 'story_complete'); }
                    });
                }, { threshold: 0.4 });
                io.observe(end);
            }

            // wire:navigate keeps the JS context but swaps the DOM: refresh the
            // locale global (for a language switch) and re-arm the story watcher.
            document.addEventListener('livewire:navigated', function () {
                op('setGlobalProperties', { locale: document.documentElement.lang || 'bg' });
                watchStoryEnd();
            });

            // Contact form: count only a server-confirmed submit, never a failed
            // validation. The component dispatches `contact-submitted` from
            // submit() once the message is persisted; Livewire re-emits server
            // events as a native window CustomEvent (the same channel Alpine's
            // x-on:event.window uses), so listen there directly.
            window.addEventListener('contact-submitted', function () { op('track', 'contact_submit'); });

            // Initial load (e.g. a direct hit on the story page).
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', watchStoryEnd);
            } else {
                watchStoryEnd();
            }
        })();
    </script>
@endif
