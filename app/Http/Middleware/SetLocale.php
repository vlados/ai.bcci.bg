<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the active locale for every web request.
 *
 * The locale is read from the route-name prefix (bg.about / en.about). Using
 * the name rather than a route default keeps generated URLs clean (no
 * ?locale= query string). Subsequent Livewire update requests carry no named
 * localized route, so they fall back to the session — keeping the language
 * consistent through interactions like the contact form.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = array_keys(config('site.locales'));
        $default = config('site.default_locale', 'bg');

        $routeLocale = null;
        if ($name = $request->route()?->getName()) {
            $prefix = explode('.', $name)[0];
            if (in_array($prefix, $locales, true)) {
                $routeLocale = $prefix;
            }
        }

        $locale = $routeLocale ?? $request->session()->get('locale') ?? $default;

        if (! in_array($locale, $locales, true)) {
            $locale = $default;
        }

        app()->setLocale($locale);

        if ($routeLocale) {
            $request->session()->put('locale', $routeLocale);
        }

        return $next($request);
    }
}
