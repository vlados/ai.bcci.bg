<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Arm motion before paint, but only when the visitor allows it. --}}
    <script>
        if (!window.matchMedia || !matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.classList.add('motion-on');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:wght@400;500;600;700&family=Sofia+Sans+Condensed:wght@400;500;700&family=PT+Serif:ital,wght@0,400;1,400&display=swap" rel="stylesheet">

    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%23EE272F'/%3E%3Ctext x='16' y='23' font-family='Arial,sans-serif' font-size='17' font-weight='bold' fill='white' text-anchor='middle'%3EAI%3C/text%3E%3C/svg%3E">

    <link rel="alternate" type="application/rss+xml"
          title="{{ __('Новини') }}" href="{{ route(app()->getLocale().'.feed') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- OpenPanel analytics (production host only) --}}
    @include('partials.analytics')

    {{-- Full SEO/GEO head: title, meta, canonical, hreflang, OG, Twitter, JSON-LD --}}
    {!! app(\App\Support\Seo::class)->render() !!}
</head>
<body class="font-sans bg-white text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-3 focus:rounded focus:bg-ink focus:px-4 focus:py-2 focus:text-white">
        {{ __('Прескочи до съдържанието') }}
    </a>

    @include('partials.topbar')
    @include('partials.header')

    @include('partials.breadcrumbs')

    {{-- tabindex="-1" so the skip link can actually move focus here, and so
         app.js can restore focus after a wire:navigate page swap. --}}
    <main id="main" tabindex="-1" class="focus:outline-none">
        {{ $slot }}
    </main>

    @include('partials.footer')
</body>
</html>
