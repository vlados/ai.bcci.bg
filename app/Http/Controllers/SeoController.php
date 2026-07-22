<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** Page keys that have a URL in every locale. */
    protected array $pageKeys = ['home', 'about', 'education', 'positions', 'survey', 'partners', 'news', 'contacts'];

    public function sitemap(): Response
    {
        $locales = array_keys(config('site.locales'));
        $default = config('site.default_locale', 'bg');

        // Each entry: [routeName base, params, lastmod]
        $entries = [];
        foreach ($this->pageKeys as $key) {
            $entries[] = ['base' => $key, 'params' => [], 'lastmod' => null];
        }
        foreach (NewsArticle::published()->latest('published_at')->get() as $article) {
            $entries[] = [
                'base' => 'news.show',
                'params' => ['article' => $article->slug],
                'lastmod' => optional($article->updated_at)->toAtomString(),
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($entries as $entry) {
            foreach ($locales as $locale) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e(route($locale.'.'.$entry['base'], $entry['params'])).'</loc>'."\n";
                if ($entry['lastmod']) {
                    $xml .= '    <lastmod>'.e($entry['lastmod']).'</lastmod>'."\n";
                }
                foreach ($locales as $alt) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="'.e($alt).'" href="'.e(route($alt.'.'.$entry['base'], $entry['params'])).'"/>'."\n";
                }
                $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e(route($default.'.'.$entry['base'], $entry['params'])).'"/>'."\n";
                $xml .= "  </url>\n";
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /livewire',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * llms.txt — the emerging convention (llmstxt.org) that gives generative
     * answer-engines a clean, authoritative map of the site. Core of the GEO effort.
     */
    public function llms(): Response
    {
        $loc = config('site.default_locale', 'bg');
        app()->setLocale($loc);

        $global = Page::forKey('global');
        $home = Page::forKey('home');
        $about = Page::forKey('about');

        $orgName = config('site.org.name')[$loc];
        $parent = config('site.org.parent.name')[$loc];
        $summary = $global->get('seo_description') ?: $home->get('hero_intro');

        $md = "# {$orgName}\n\n";
        $md .= '> '.$this->oneLine($summary)."\n\n";
        $md .= $this->oneLine($about->get('hero_intro'))."\n\n";

        $md .= "## ".($loc === 'bg' ? 'Ключови страници' : 'Key pages')."\n\n";
        $descriptions = [
            'about' => $about->get('hero_intro'),
            'education' => Page::forKey('education')->get('hero_intro'),
            'positions' => Page::forKey('positions')->get('hero_intro'),
            'survey' => Page::forKey('survey')->get('hero_intro'),
            'partners' => Page::forKey('partners')->get('hero_intro'),
            'news' => Page::forKey('news')->get('hero_intro'),
            'contacts' => Page::forKey('contacts')->get('hero_intro'),
        ];
        foreach ($descriptions as $key => $desc) {
            $label = config("site.nav.$key.$loc");
            $md .= "- [{$label}](".route($loc.'.'.$key)."): ".$this->oneLine($desc)."\n";
        }

        $md .= "\n## ".($loc === 'bg' ? 'Факти' : 'Facts')."\n\n";
        $md .= "- ".($loc === 'bg' ? 'Организация' : 'Organization').": {$orgName}\n";
        $md .= "- ".($loc === 'bg' ? 'Част от' : 'Part of').": {$parent} (".config('site.org.parent.url').")\n";
        $md .= "- ".($loc === 'bg' ? 'Област' : 'Area served').": ".config('site.org.area_served')[$loc]."\n";
        $md .= "- ".($loc === 'bg' ? 'Езици' : 'Languages').": ".($loc === 'bg' ? 'български, английски' : 'Bulgarian, English')."\n";
        if ($global->get('contact_email')) {
            $md .= "- ".($loc === 'bg' ? 'Имейл' : 'Email').": ".$global->get('contact_email')."\n";
        }
        $md .= "- English version: ".route('en.home')."\n";

        $recent = NewsArticle::published()->latest('published_at')->take(10)->get();
        if ($recent->isNotEmpty()) {
            $md .= "\n## ".($loc === 'bg' ? 'Последни новини' : 'Latest news')."\n\n";
            foreach ($recent as $a) {
                $date = $a->published_at?->format('Y-m-d');
                $md .= "- {$date} — [".$this->oneLine($a->tr('title'))."](".route($loc.'.news.show', $a).")\n";
            }
        }

        return response($md, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function feed(Request $request): Response
    {
        $default = config('site.default_locale', 'bg');
        $prefix = explode('.', (string) $request->route()?->getName())[0];
        $loc = in_array($prefix, array_keys(config('site.locales')), true) ? $prefix : $default;
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

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    protected function oneLine(?string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)));
    }
}
