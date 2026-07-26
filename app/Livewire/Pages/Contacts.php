<?php

namespace App\Livewire\Pages;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Support\Facades\Mail;
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

    /**
     * The site runs with bg as both locale and fallback and ships no
     * lang/bg/validation.php, so the framework's own messages resolve to raw
     * keys — a visitor would literally read "validation.required". Spelling
     * the messages out keeps them human; en.json carries the English side.
     */
    protected function messages(): array
    {
        return [
            'name.required' => __('Моля, въведете вашето име.'),
            'name.max' => __('Името може да бъде най-много 255 знака.'),
            'email.required' => __('Моля, въведете вашия имейл.'),
            'email.email' => __('Моля, въведете валиден имейл адрес.'),
            'email.max' => __('Имейлът може да бъде най-много 255 знака.'),
            'message.required' => __('Моля, напишете вашето съобщение.'),
            'message.max' => __('Съобщението може да бъде най-много 5000 знака.'),
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();

        $contactMessage = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'locale' => app()->getLocale(),
        ]);

        $this->notifyCouncil($contactMessage);

        $this->reset(['name', 'email', 'message']);
        $this->sent = true;

        // Analytics: a browser event the OpenPanel glue turns into a
        // `contact_submit` — fired here so it counts only a persisted message,
        // never a failed validation or a mere button click.
        $this->dispatch('contact-submitted');
    }

    /**
     * Mail the message to whatever address the CMS lists as the contact one.
     *
     * Sent inline rather than queued: the queue driver is `database`, so a box
     * without a running worker would silently park every message in the jobs
     * table. The row is already persisted by the time we get here, so a mailer
     * that is down is logged and swallowed — it must never turn a submission
     * the visitor already made into an error screen.
     */
    protected function notifyCouncil(ContactMessage $contactMessage): void
    {
        $recipient = Page::forKey('global')->get('contact_email') ?: config('mail.from.address');

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new ContactMessageReceived($contactMessage));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function render()
    {
        $page = $this->loadPage();
        $this->baseSeo($page);

        return view('livewire.pages.contacts', ['page' => $page]);
    }
}
