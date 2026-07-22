<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Navigation + locale helpers shared by the layout, the language switcher
 * and the SEO service. Route names are locale-prefixed (bg.home, en.about,
 * bg.news.show), so the current page maps 1:1 to its counterpart locale.
 */
class SiteNav
{
    /** The page key of the current route, e.g. "home", "about", "news". */
    public static function currentKey(): string
    {
        $name = Route::currentRouteName() ?? '';
        $parts = explode('.', $name);

        return $parts[1] ?? 'home';
    }

    /** Top navigation items for the active locale. */
    public static function items(): array
    {
        $locale = app()->getLocale();
        $current = self::currentKey();
        $items = [];

        foreach (config('site.nav') as $key => $labels) {
            $routeName = $locale.'.'.$key;
            $items[] = [
                'key' => $key,
                'label' => $labels[$locale] ?? $labels[config('site.default_locale')] ?? $key,
                'url' => Route::has($routeName) ? route($routeName) : url('/'),
                'active' => $key === $current,
            ];
        }

        return $items;
    }

    /**
     * The current page's URL in every locale, for the language switcher and
     * for hreflang alternates. Carries route params (e.g. a news slug) across.
     */
    public static function localeAlternates(): array
    {
        $currentName = Route::currentRouteName() ?? (app()->getLocale().'.home');
        $base = Str::after($currentName, '.');            // e.g. "home" or "news.show"
        $params = Route::current()?->parameters() ?? [];

        $out = [];
        foreach (config('site.locales') as $locale => $label) {
            $name = $locale.'.'.$base;
            $url = Route::has($name) ? route($name, $params) : route($locale.'.home');
            $out[] = [
                'locale' => $locale,
                'label' => $label,
                'url' => $url,
                'active' => $locale === app()->getLocale(),
            ];
        }

        return $out;
    }
}
