<?php

namespace App\Livewire\Pages;

use App\Models\NewsArticle;
use Livewire\WithPagination;

class News extends SitePage
{
    use WithPagination;

    protected string $pageKey = 'news';

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.news', [
            'page' => $page,
            'articles' => NewsArticle::published()->latest('published_at')->paginate(9),
        ]);
    }
}
