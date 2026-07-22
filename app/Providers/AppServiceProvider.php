<?php

namespace App\Providers;

use App\Models\Page;
use App\Support\Seo;
use App\Support\SiteNav;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // One SEO builder per request; populated by each full-page component
        // and rendered by the layout's <head>.
        $this->app->scoped(Seo::class, fn () => new Seo());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Chrome shared by the layout + partials: global content, nav, locale switch.
        View::composer(['layouts.app', 'partials.*', 'livewire.pages.*'], function ($view) {
            $view->with('global', Page::forKey('global'));
            $view->with('nav', SiteNav::items());
            $view->with('localeAlternates', SiteNav::localeAlternates());
            $view->with('currentKey', SiteNav::currentKey());
        });
    }
}
