<?php

namespace App\Http\Controllers;

use App\Support\FeedBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
| Only the two endpoints that must be served live.
|
| sitemap.xml and llms.txt are static files in public/, written by the scheduled
| `seo:generate` command — see App\Support\SitemapBuilder / LlmsTxtBuilder.
*/
class SeoController extends Controller
{
    /**
     * robots.txt.
     *
     * Deliberately does NOT disallow /livewire. robots.txt matching is a plain
     * prefix match, and Livewire serves its runtime from a hashed sibling path
     * (/livewire-<hash>/livewire.js), so that rule silently blocked the site's
     * own JavaScript from every crawler. The only other thing under the prefix
     * is the update endpoint, which is POST-only and 405s on GET — there is
     * nothing there for a crawler to reach.
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /up',
            '',
            // Static file, written by the scheduled `seo:generate` command.
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function feed(Request $request, FeedBuilder $feed): Response
    {
        $default = config('site.default_locale', 'bg');
        $prefix = explode('.', (string) $request->route()?->getName())[0];
        $loc = in_array($prefix, array_keys(config('site.locales')), true) ? $prefix : $default;

        return response($feed->render($loc), 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }
}
