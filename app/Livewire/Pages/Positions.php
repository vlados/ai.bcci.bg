<?php

namespace App\Livewire\Pages;

use App\Models\Position;

class Positions extends SitePage
{
    protected string $pageKey = 'positions';

    public function render()
    {
        $page = $this->loadPage();
        $positions = Position::published()->orderByDesc('document_date')->get();

        $seo = $this->baseSeo($page);

        // ItemList of published positions — a strong structured signal for GEO.
        if ($positions->isNotEmpty()) {
            $seo->addJsonLd([
                '@type' => 'ItemList',
                'name' => $this->navLabel('positions'),
                'itemListElement' => $positions->values()->map(fn ($p, $i) => array_filter([
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $p->tr('title'),
                    'url' => $p->pdf_path ? asset('storage/'.$p->pdf_path) : null,
                ]))->all(),
            ]);
        }

        return view('livewire.pages.positions', [
            'page' => $page,
            'positions' => $positions,
        ]);
    }
}
