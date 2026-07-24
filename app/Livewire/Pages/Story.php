<?php

namespace App\Livewire\Pages;

use App\Support\Seo;
use Livewire\Component;

/**
 * The AI-adoption data story — a standalone scroll-driven page, not a CMS Page.
 *
 * All figures come from config/eurostat.php, where each carries its dataset,
 * population and extraction date. The page is authored to read as a finished
 * static document; the scroll motion (see resources/js/app.js) is pure
 * enhancement, gated on the site's motion switch and torn down cleanly. The
 * global Livewire layout (config/livewire.php) wraps every page component.
 */
class Story extends Component
{
    public function render()
    {
        $loc = app()->getLocale();

        $title = $loc === 'bg'
            ? 'Разликата, която се разширява: изкуственият интелект в българския бизнес'
            : 'The widening gap: artificial intelligence in Bulgarian business';

        $description = $loc === 'bg'
            ? 'Данни на Евростат за 2025 г.: 8,55% от българските предприятия използват ИИ при 19,95% за ЕС. Как се разширява разликата, какво я движи и защо не е въпрос на размер.'
            : 'Eurostat data for 2025: 8.55% of Bulgarian enterprises use AI against 19.95% across the EU. How the gap is widening, what drives it, and why it is not a question of size.';

        /** @var Seo $seo */
        $seo = app(Seo::class);
        $seo->title($title)
            ->description($description)
            ->canonical(url()->current())
            ->type('article')
            ->breadcrumbs([
                ['name' => config("site.nav.home.$loc") ?? 'Начало', 'url' => route($loc.'.home')],
                ['name' => $title, 'url' => url()->current()],
            ])
            ->addJsonLd([
                '@type' => 'Article',
                'headline' => $title,
                'description' => $description,
                'inLanguage' => $loc,
                'mainEntityOfPage' => url()->current(),
                'author' => ['@type' => 'Organization', '@id' => rtrim(url('/'), '/').'/#organization'],
                'publisher' => ['@id' => rtrim(url('/'), '/').'/#organization'],
                'isBasedOn' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table',
            ]);

        return view('livewire.pages.story', [
            'loc' => $loc,
            'adoption' => config('eurostat.ai_adoption'),
            'bySize' => config('eurostat.ai_adoption_by_size'),
            'ranking' => config('eurostat.ai_adoption_ranking'),
            'barriers' => config('eurostat.ai_barriers'),
            'individuals' => config('eurostat.ai_individuals'),
        ]);
    }
}
