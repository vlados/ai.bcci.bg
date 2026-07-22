<?php

namespace App\Livewire\Pages;

use App\Models\NewsArticle;

class Home extends SitePage
{
    protected string $pageKey = 'home';

    public function render()
    {
        $page = $this->loadPage();
        $loc = app()->getLocale();

        // Home's title is the organisation name itself (no extra brand suffix).
        $this->baseSeo($page)->title(
            $page->get('meta_title') ?: (config('site.org.name')[$loc] ?? ''),
            appendBrand: false,
        );

        return view('livewire.pages.home', [
            'page' => $page,
            'news' => NewsArticle::published()->latest('published_at')->take(3)->get(),
        ]);
    }
}
