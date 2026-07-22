<?php

use App\Http\Controllers\SeoController;
use App\Livewire\Pages\About;
use App\Livewire\Pages\Contacts;
use App\Livewire\Pages\Education;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\News;
use App\Livewire\Pages\NewsShow;
use App\Livewire\Pages\Partners;
use App\Livewire\Pages\Positions;
use App\Livewire\Pages\Survey;
use Illuminate\Support\Facades\Route;

/*
| Public site — full-page Livewire components.
|
| The default locale (bg) is served unprefixed; other locales live under their
| prefix (/en, /en/about). Route names are locale-namespaced (bg.home, en.about)
| and SetLocale reads the locale from that name prefix — so the switcher maps a
| page 1:1 to its counterpart and URLs stay clean (no ?locale= query string).
*/

$default = config('site.default_locale', 'bg');

foreach (array_keys(config('site.locales')) as $locale) {
    Route::prefix($locale === $default ? '' : $locale)
        ->name($locale.'.')
        ->group(function () {
            Route::get('/', Home::class)->name('home');
            Route::get('about', About::class)->name('about');
            Route::get('education', Education::class)->name('education');
            Route::get('positions', Positions::class)->name('positions');
            Route::get('survey', Survey::class)->name('survey');
            Route::get('partners', Partners::class)->name('partners');
            Route::get('news', News::class)->name('news');
            Route::get('news/{article:slug}', NewsShow::class)->name('news.show');
            Route::get('contacts', Contacts::class)->name('contacts');

            // Per-locale RSS feed for news.
            Route::get('feed', [SeoController::class, 'feed'])->name('feed');
        });
}

// robots.txt stays a route because it's cheap and has no static counterpart.
// sitemap.xml and llms.txt are deliberately absent: they're static files in
// public/, written by the scheduled `seo:generate` command and served without
// booting Laravel at all.
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');
