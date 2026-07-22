<?php

namespace App\Livewire\Pages;

use App\Models\NewsArticle;
use App\Support\Seo;

class NewsShow extends SitePage
{
    protected string $pageKey = 'news';

    public NewsArticle $article;

    public function mount(NewsArticle $article): void
    {
        abort_unless($article->is_published, 404);
        $this->article = $article;
    }

    public function render()
    {
        $loc = app()->getLocale();
        $article = $this->article;

        $title = $article->tr('meta_title') ?: $article->tr('title');
        $desc = $article->tr('meta_description') ?: $article->tr('excerpt') ?: $article->tr('title');
        $image = $article->image ? asset('storage/'.$article->image) : null;
        $published = optional($article->published_at)?->toIso8601String();

        /** @var Seo $seo */
        $seo = app(Seo::class);
        $seo->title($title)
            ->description($desc)
            ->canonical(url()->current())
            ->article($published, optional($article->updated_at)?->toIso8601String())
            ->image($article->image ? 'storage/'.$article->image : null)
            ->breadcrumbs([
                ['name' => $this->navLabel('home'), 'url' => route($loc.'.home')],
                ['name' => $this->navLabel('news'), 'url' => route($loc.'.news')],
                ['name' => $article->tr('title'), 'url' => route($loc.'.news.show', $article)],
            ])
            ->addJsonLd(array_filter([
                '@type' => 'NewsArticle',
                'headline' => $article->tr('title'),
                'description' => $desc,
                'image' => $image,
                'datePublished' => $published,
                'dateModified' => optional($article->updated_at)?->toIso8601String(),
                'inLanguage' => $loc,
                'mainEntityOfPage' => url()->current(),
                'author' => ['@type' => 'Organization', '@id' => rtrim(url('/'), '/').'/#organization'],
                'publisher' => ['@id' => rtrim(url('/'), '/').'/#organization'],
            ]));

        return view('livewire.pages.news-show', [
            'article' => $article,
            'more' => NewsArticle::published()
                ->whereKeyNot($article->getKey())
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
