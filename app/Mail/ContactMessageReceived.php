<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies the Council that someone used the contact form.
 *
 * Reply-to is the visitor, so answering from the inbox goes straight back to
 * them — the From address stays the site's own so the mail passes SPF/DKIM.
 */
class ContactMessageReceived extends Mailable
{
    use SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Ново съобщение от формата за контакт').' — '.$this->contactMessage->name,
            replyTo: [new Address($this->contactMessage->email, $this->contactMessage->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contact-message');
    }
}
