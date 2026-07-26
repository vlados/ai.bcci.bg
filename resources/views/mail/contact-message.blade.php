<x-mail::message>
# {{ __('Ново съобщение от формата за контакт') }}

**{{ __('Име') }}:** {{ $contactMessage->name }}
**{{ __('Имейл') }}:** {{ $contactMessage->email }}
**{{ __('Език') }}:** {{ $contactMessage->locale }}

---

{{ $contactMessage->message }}

---

<x-mail::button :url="route('filament.admin.resources.contact-messages.index')">
{{ __('Отвори в администрацията') }}
</x-mail::button>
</x-mail::message>
