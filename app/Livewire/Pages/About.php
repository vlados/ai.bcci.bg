<?php

namespace App\Livewire\Pages;

use App\Models\TeamMember;

class About extends SitePage
{
    protected string $pageKey = 'about';

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page)->type('website');

        return view('livewire.pages.about', [
            'page' => $page,
            'team' => TeamMember::orderBy('sort_order')->get(),
        ]);
    }
}
