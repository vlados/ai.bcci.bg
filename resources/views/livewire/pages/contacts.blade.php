@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1.3fr_1fr] gap-8 lg:gap-14 items-start">
        <form wire:submit="submit" class="border border-line px-5 py-6 sm:px-10 sm:pt-9 sm:pb-10">
            <h2 class="text-2xl font-bold mb-6">{{ $page->get('form_title') }}</h2>

            @if ($sent)
                <div class="mb-6 border border-brand bg-[#FDF3F3] text-ink-soft px-4 py-3 text-[15px]">
                    {{ __('Благодарим ви! Съобщението беше изпратено.') }}
                </div>
            @endif

            <div class="grid gap-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <input type="text" wire:model="name" placeholder="{{ __('Вашето име') }}"
                               class="border border-[#C9C8C3] px-4 py-[13px] text-[15px] outline-none w-full placeholder:text-[#9C9DA1] focus:border-ink">
                        @error('name') <span class="text-brand text-[13px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="email" wire:model="email" placeholder="{{ __('Вашият имейл') }}"
                               class="border border-[#C9C8C3] px-4 py-[13px] text-[15px] outline-none w-full placeholder:text-[#9C9DA1] focus:border-ink">
                        @error('email') <span class="text-brand text-[13px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <textarea wire:model="message" placeholder="{{ __('Вашето съобщение') }}" rows="6"
                              class="border border-[#C9C8C3] px-4 py-[13px] text-[15px] outline-none resize-y w-full placeholder:text-[#9C9DA1] focus:border-ink"></textarea>
                    @error('message') <span class="text-brand text-[13px] mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <button type="submit" class="bg-brand text-white px-[26px] py-3.5 font-semibold text-[15px] hover:bg-brand-dark">
                        <span wire:loading.remove wire:target="submit">{{ __('Изпратете') }}</span>
                        <span wire:loading wire:target="submit">{{ __('Изпращане…') }}</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-[26px]">
            <div class="border border-line">
                <div class="px-7 py-5 border-b border-line text-[13.5px] font-bold tracking-[1.8px]">{{ $page->get('card_title') }}</div>
                @if ($global->get('contact_email'))
                    <div class="px-7 py-5 border-b border-line">
                        <div class="text-[13px] text-faint mb-1">{{ __('Имейл') }}</div>
                        <a href="mailto:{{ $global->get('contact_email') }}" class="text-base font-semibold hover:text-brand">{{ $global->get('contact_email') }}</a>
                    </div>
                @endif
                @if ($global->get('contact_phone'))
                    <div class="px-7 py-5 border-b border-line">
                        <div class="text-[13px] text-faint mb-1">{{ __('Телефон') }}</div>
                        <div class="text-base font-semibold">{{ $global->get('contact_phone') }}</div>
                    </div>
                @endif
                @if ($global->get('contact_address'))
                    <div class="px-7 py-5">
                        <div class="text-[13px] text-faint mb-1">{{ __('Адрес') }}</div>
                        <div class="text-base font-semibold">{!! nl2br(e($global->get('contact_address'))) !!}</div>
                        @if ($global->get('contact_address_note'))
                            <div class="text-sm text-muted mt-1.5">{{ $global->get('contact_address_note') }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="bg-ink px-8 pt-[30px] pb-[34px]">
                <div class="text-[17px] font-bold text-white mb-2.5">{{ $global->get('newsletter_title') }}</div>
                <p class="text-sm leading-[1.6] text-[#A9AAAE] mb-[18px]">{{ $global->get('newsletter_text') }}</p>
                <a href="{{ $global->get('newsletter_url') ?: 'https://www.bcci.bg' }}" target="_blank" rel="noopener"
                   class="inline-block bg-brand text-white px-5 py-3 font-semibold text-sm hover:bg-brand-dark">{{ __('Абонирай ме') }}</a>
            </div>
        </div>
    </div>
</div>
