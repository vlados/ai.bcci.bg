<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Runs for every web request — including Livewire's POST /livewire/update —
        // so the chosen locale survives interactions, not just full-page loads.
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Behind Caddy, honour the forwarded scheme so canonical/hreflang/OG
        // URLs are built as https rather than the http seen on the socket.
        $middleware->trustProxies(at: '*');

        // Without this, any Host header that reaches the app mints a complete,
        // self-consistent set of canonical/hreflang/JSON-LD URLs for itself —
        // i.e. an instant duplicate of the whole site on any hostname pointed
        // at the server. Resolved lazily so config is available, and Laravel
        // skips the check entirely in local/testing.
        //
        // The host this deployment is actually reached on (APP_URL) is always
        // trusted, or a staging deployment would 400 on its own domain. Add
        // more with TRUSTED_HOSTS=a.example,b.example.
        $middleware->trustHosts(at: fn () => array_values(array_unique(array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST),
            config('site.seo.production_host'),
            ...array_map('trim', explode(',', (string) env('TRUSTED_HOSTS'))),
        ]))), subdomains: false);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
