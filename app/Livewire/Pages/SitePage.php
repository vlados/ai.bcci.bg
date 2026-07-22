<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use App\Support\Seo;
use Livewire\Component;

/**
 * Base for the public full-page components. Loads the matching Page content
 * record and wires up per-page SEO (title/description/canonical/breadcrumbs)
 * with sensible fallbacks derived from the page's own hero copy.
 */
abstract class SitePage extends Component
{
    protected string $pageKey = 'home';

    protected function loadPage(): Page
    {
        return Page::forKey($this->pageKey);
    }

    protected function navLabel(string $key): string
    {
        $loc = app()->getLocale();

        return config("site.nav.$key.$loc") ?? config("site.nav.$key.bg") ?? $key;
    }

    protected function baseSeo(Page $page): Seo
    {
        $loc = app()->getLocale();
        $seo = app(Seo::class);

        $seo->title($page->get('meta_title') ?: $this->navLabel($this->pageKey))
            ->description($page->get('meta_description') ?: $page->get('hero_intro'))
            ->canonical(url()->current());

        $crumbs = [['name' => $this->navLabel('home'), 'url' => route($loc.'.home')]];
        if ($this->pageKey !== 'home') {
            $crumbs[] = ['name' => $this->navLabel($this->pageKey), 'url' => route($loc.'.'.$this->pageKey)];
        }
        $seo->breadcrumbs($crumbs);

        return $seo;
    }
}
