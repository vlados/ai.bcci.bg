<?php

namespace App\Livewire\Pages;

class Education extends SitePage
{
    protected string $pageKey = 'education';

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.education', ['page' => $page]);
    }
}
