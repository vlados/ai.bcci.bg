<?php

namespace App\Livewire\Pages;

class Survey extends SitePage
{
    protected string $pageKey = 'survey';

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.survey', ['page' => $page]);
    }
}
