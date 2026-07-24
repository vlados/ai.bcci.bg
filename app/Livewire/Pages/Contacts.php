<?php

namespace App\Livewire\Pages;

use App\Models\ContactMessage;
use Livewire\Attributes\Validate;

class Contacts extends SitePage
{
    protected string $pageKey = 'contacts';

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:5000')]
    public string $message = '';

    public bool $sent = false;

    public function submit(): void
    {
        $data = $this->validate();

        ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'locale' => app()->getLocale(),
        ]);

        $this->reset(['name', 'email', 'message']);
        $this->sent = true;

        // Analytics: a browser event the OpenPanel glue turns into a
        // `contact_submit` — fired here so it counts only a persisted message,
        // never a failed validation or a mere button click.
        $this->dispatch('contact-submitted');
    }

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.contacts', ['page' => $page]);
    }
}
