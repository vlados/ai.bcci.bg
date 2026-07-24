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
use App\Livewire\Pages\Story;
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

            // Standalone scroll-driven data story on the AI-adoption gap. Named
            // in the locale group like the rest, so SiteNav derives its hreflang
            // alternates and language-switcher target automatically.
            Route::get('ai-adoption-2026', Story::class)->name('story');
            Route::get('contacts', Contacts::class)->name('contacts');

            // Per-locale RSS feed for news.
            Route::get('feed', [SeoController::class, 'feed'])->name('feed');
        });
}

/*
| Legacy campaign URLs.
|
| ai.bcci.bg used to serve the "AI in Bulgarian Business 2026" campaign at
| /ai-business-2026 (and /ai-business-2026/survey). This site replaces that
| host and has no such route, so without these the URLs 404 on cutover —
| and they carry the campaign's accumulated links, including from LinkedIn.
|
| One hop, permanent, query string preserved so campaign tracking survives.
| The wildcard catches any deeper campaign path in a single redirect rather
| than letting it fall through to a 404.
|
| CAUTION: the survey page's CTA points at prouchvane.bg/ai-business-2026,
| which is where the live questionnaire is. Do NOT also redirect
| prouchvane.bg/ai-business-2026 back here — that closes the loop
| /survey → prouchvane.bg → ai.bcci.bg → /survey and traps the visitor.
| Consolidate that domain only after the questionnaire itself has moved.
*/
Route::get('ai-business-2026/{path?}', fn () => redirect()->route(
    $default.'.survey',
    request()->query(),
    301,
))->where('path', '.*')->name('legacy.campaign');

// robots.txt stays a route because it's cheap and has no static counterpart.
// sitemap.xml and llms.txt are deliberately absent: they're static files in
// public/, written by the scheduled `seo:generate` command and served without
// booting Laravel at all.
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');
