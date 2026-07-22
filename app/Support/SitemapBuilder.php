<?php

namespace App\Support;

use App\Models\NewsArticle;
use App\Models\Page;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Builds the sitemap for every public URL, in every locale.
 *
 * One builder, two callers: the scheduled `sitemap:generate` command writes the
 * result to public/sitemap.xml (what crawlers actually get), and SeoController
 * renders it live as a fallback for as long as that file doesn't exist. Keeping
 * a single source here is what stops the two from drifting apart.
 */
class SitemapBuilder
{
    /** Page keys that have a URL in every locale, with their crawl priority. */
    protected const PAGES = [
        'home' => 1.0,
        'about' => 0.8,
        'education' => 0.8,
        'positions' => 0.8,
        'survey' => 0.8,
        'partners' => 0.7,
        'news' => 0.9,
        'contacts' => 0.6,
    ];

    public function build(): Sitemap
    {
        $sitemap = Sitemap::create();
        $locales = array_keys(config('site.locales'));
        $default = config('site.default_locale', 'bg');

        foreach (self::PAGES as $key => $priority) {
            $lastmod = Page::where('key', $key)->value('updated_at');

            foreach ($locales as $locale) {
                $url = Url::create(route($locale.'.'.$key))
                    ->setPriority($priority)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);

                if ($lastmod) {
                    $url->setLastModificationDate($lastmod);
                }

                $this->addAlternates($url, $locales, $default, $key);
                $sitemap->add($url);
            }
        }

        foreach (NewsArticle::published()->latest('published_at')->get() as $article) {
            foreach ($locales as $locale) {
                $url = Url::create(route($locale.'.news.show', ['article' => $article->slug]))
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);

                if ($article->updated_at) {
                    $url->setLastModificationDate($article->updated_at);
                }

                $this->addAlternates($url, $locales, $default, 'news.show', ['article' => $article->slug]);
                $sitemap->add($url);
            }
        }

        return $sitemap;
    }

    /** Every locale variant of a URL points at all the others (plus x-default). */
    protected function addAlternates(Url $url, array $locales, string $default, string $base, array $params = []): void
    {
        foreach ($locales as $alt) {
            $url->addAlternate(route($alt.'.'.$base, $params), $alt);
        }

        $url->addAlternate(route($default.'.'.$base, $params), 'x-default');
    }
}
