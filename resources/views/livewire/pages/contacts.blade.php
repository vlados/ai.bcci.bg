@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1.3fr_1fr] gap-8 lg:gap-14 items-start">
        <form wire:submit="submit" class="border border-line px-5 py-6 sm:px-10 sm:pt-9 sm:pb-10">
            <h2 class="text-2xl font-bold mb-6">{{ $page->get('form_title') }}</h2>

            {{-- role="status" so the confirmation is announced, not just shown. --}}
            @if ($sent)
                <div role="status" class="mb-6 border border-brand bg-brand-wash text-ink-soft px-4 py-3 text-base">
                    {{ __('Благодарим ви! Съобщението беше изпратено.') }}
                </div>
            @endif

            {{-- Every field carries a real <label>: a placeholder is not an
                 accessible name, and it vanishes the moment you start typing. --}}
            <div class="grid gap-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact-name" class="block text-sm font-semibold mb-1.5">{{ __('Вашето име') }}</label>
                        <input type="text" id="contact-name" wire:model="name" autocomplete="name"
                               @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror
                               class="border border-muted px-4 py-3 text-base w-full focus:border-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">
                        @error('name') <span id="contact-name-error" class="text-brand-dark text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="contact-email" class="block text-sm font-semibold mb-1.5">{{ __('Вашият имейл') }}</label>
                        <input type="email" id="contact-email" wire:model="email" autocomplete="email"
                               @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror
                               class="border border-muted px-4 py-3 text-base w-full focus:border-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">
                        @error('email') <span id="contact-email-error" class="text-brand-dark text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label for="contact-message" class="block text-sm font-semibold mb-1.5">{{ __('Вашето съобщение') }}</label>
                    <textarea id="contact-message" wire:model="message" rows="6"
                              @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror
                              class="border border-muted px-4 py-3 text-base resize-y w-full focus:border-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink"></textarea>
                    @error('message') <span id="contact-message-error" class="text-brand-dark text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <button type="submit" class="bg-brand text-white px-6 py-3.5 font-semibold text-base hover:bg-brand-dark">
                        <span wire:loading.remove wire:target="submit">{{ __('Изпратете') }}</span>
                        <span wire:loading wire:target="submit">{{ __('Изпращане…') }}</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-6">
            <div class="border border-line">
                <div class="px-7 py-5 border-b border-line text-sm font-bold tracking-widest">{{ $page->get('card_title') }}</div>
                @if ($global->get('contact_email'))
                    <div class="px-7 py-5 border-b border-line">
                        <div class="text-sm text-faint mb-1">{{ __('Имейл') }}</div>
                        <a href="mailto:{{ $global->get('contact_email') }}" class="text-base font-semibold hover:text-brand">{{ $global->get('contact_email') }}</a>
                    </div>
                @endif
                @if ($global->get('contact_phone'))
                    <div class="px-7 py-5 border-b border-line">
                        <div class="text-sm text-faint mb-1">{{ __('Телефон') }}</div>
                        <div class="text-base font-semibold">{{ $global->get('contact_phone') }}</div>
                    </div>
                @endif
                @if ($global->get('contact_address'))
                    <div class="px-7 py-5">
                        <div class="text-sm text-faint mb-1">{{ __('Адрес') }}</div>
                        <div class="text-base font-semibold">{!! nl2br(e($global->get('contact_address'))) !!}</div>
                        @if ($global->get('contact_address_note'))
                            <div class="text-sm text-muted mt-1.5">{{ $global->get('contact_address_note') }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="bg-ink px-8 pt-8 pb-8">
                <div class="text-lg font-bold text-white mb-2.5">{{ $global->get('newsletter_title') }}</div>
                <p class="text-sm leading-relaxed text-on-ink mb-4">{{ $global->get('newsletter_text') }}</p>
                <a href="{{ $global->get('newsletter_url') ?: 'https://www.bcci.bg' }}" target="_blank" rel="noopener"
                   class="inline-block bg-brand text-white px-5 py-3 font-semibold text-sm hover:bg-brand-dark">{{ __('Абонирай ме') }}</a>
            </div>
        </div>
    </div>
</div>
