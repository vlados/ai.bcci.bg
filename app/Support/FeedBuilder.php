<?php

namespace App\Support;

use App\Concerns\FlattensText;
use App\Models\NewsArticle;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

/**
 * Per-locale RSS feed for news.
 *
 * Unlike the sitemap and llms.txt this stays a served route, not a generated
 * file: a feed's whole value is freshness (a nightly file would show news up to
 * a day late), and /feed and /en/feed have no file extension — turning them into
 * static files would mean changing URLs people have subscribed to.
 *
 * So it's cached instead, and the cache is dropped the moment a news article or
 * a page is saved. That's fresher than a generated file and cheaper than the
 * uncached route.
 */
class FeedBuilder
{
    use FlattensText;

    protected const CACHE_KEY = 'feed.rss.';

    public function render(string $locale): string
    {
        return Cache::rememberForever(self::CACHE_KEY.$locale, fn () => $this->build($locale));
    }

    /** Called from model events — the next request rebuilds from fresh data. */
    public static function flush(): void
    {
        foreach (array_keys(config('site.locales')) as $locale) {
            Cache::forget(self::CACHE_KEY.$locale);
        }
    }

    protected function build(string $loc): string
    {
        app()->setLocale($loc);

        $articles = NewsArticle::published()->latest('published_at')->take(30)->get();
        $orgName = config('site.org.name')[$loc];
        $self = route($loc.'.feed');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n<channel>\n";
        $xml .= '  <title>'.e($orgName).' — '.e(config("site.nav.news.$loc")).'</title>'."\n";
        $xml .= '  <link>'.e(route($loc.'.news')).'</link>'."\n";
        $xml .= '  <description>'.e($this->oneLine(Page::forKey('news')->get('hero_intro'))).'</description>'."\n";
        $xml .= '  <language>'.e($loc).'</language>'."\n";
        $xml .= '  <atom:link href="'.e($self).'" rel="self" type="application/rss+xml"/>'."\n";

        foreach ($articles as $a) {
            $xml .= "  <item>\n";
            $xml .= '    <title>'.e($a->tr('title')).'</title>'."\n";
            $xml .= '    <link>'.e(route($loc.'.news.show', $a)).'</link>'."\n";
            $xml .= '    <guid isPermaLink="true">'.e(route($loc.'.news.show', $a)).'</guid>'."\n";
            if ($a->published_at) {
                $xml .= '    <pubDate>'.e($a->published_at->toRssString()).'</pubDate>'."\n";
            }
            $xml .= '    <description>'.e($this->oneLine($a->tr('excerpt') ?: $a->tr('title'))).'</description>'."\n";
            $xml .= "  </item>\n";
        }

        $xml .= "</channel>\n</rss>";

        return $xml;
    }
}
