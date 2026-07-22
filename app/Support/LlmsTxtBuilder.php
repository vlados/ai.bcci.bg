<?php

namespace App\Support;

use App\Concerns\FlattensText;
use App\Models\NewsArticle;
use App\Models\Page;

/**
 * llms.txt — the emerging convention (llmstxt.org) that gives generative
 * answer-engines a clean, authoritative map of the site. Core of the GEO effort.
 *
 * Like the sitemap, this is written to public/llms.txt by `seo:generate` rather
 * than rendered per request: it reads a row per content page plus the news list,
 * and the content changes on an editorial cadence, not a per-request one.
 */
class LlmsTxtBuilder
{
    use FlattensText;

    public function build(): string
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

        $md .= '## '.($loc === 'bg' ? 'Ключови страници' : 'Key pages')."\n\n";
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
            $md .= "- [{$label}](".route($loc.'.'.$key).'): '.$this->oneLine($desc)."\n";
        }

        $md .= "\n## ".($loc === 'bg' ? 'Факти' : 'Facts')."\n\n";
        $md .= '- '.($loc === 'bg' ? 'Организация' : 'Organization').": {$orgName}\n";
        $md .= '- '.($loc === 'bg' ? 'Част от' : 'Part of').": {$parent} (".config('site.org.parent.url').")\n";
        $md .= '- '.($loc === 'bg' ? 'Област' : 'Area served').': '.config('site.org.area_served')[$loc]."\n";
        $md .= '- '.($loc === 'bg' ? 'Езици' : 'Languages').': '.($loc === 'bg' ? 'български, английски' : 'Bulgarian, English')."\n";
        if ($global->get('contact_email')) {
            $md .= '- '.($loc === 'bg' ? 'Имейл' : 'Email').': '.$global->get('contact_email')."\n";
        }
        $md .= '- English version: '.route('en.home')."\n";

        $recent = NewsArticle::published()->latest('published_at')->take(10)->get();
        if ($recent->isNotEmpty()) {
            $md .= "\n## ".($loc === 'bg' ? 'Последни новини' : 'Latest news')."\n\n";
            foreach ($recent as $a) {
                $date = $a->published_at?->format('Y-m-d');
                $md .= "- {$date} — [".$this->oneLine($a->tr('title')).']('.route($loc.'.news.show', $a).")\n";
            }
        }

        return $md;
    }
}
