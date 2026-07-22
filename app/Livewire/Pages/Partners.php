<?php

namespace App\Livewire\Pages;

use App\Models\Partner;

class Partners extends SitePage
{
    protected string $pageKey = 'partners';

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.partners', [
            'page' => $page,
            'partners' => Partner::orderBy('sort_order')->get(),
        ]);
    }
}
