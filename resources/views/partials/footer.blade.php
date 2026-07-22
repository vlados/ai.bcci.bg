@php
    $loc = app()->getLocale();
    $navLabel = fn ($key) => config("site.nav.$key.$loc") ?? config("site.nav.$key.bg");
    $orgName = config('site.org.name')[$loc] ?? config('site.org.name')['bg'];
    $newsletterUrl = $global->get('newsletter_url');
@endphp
<footer class="bg-ink text-[#A9AAAE] relative overflow-hidden">
    <div aria-hidden="true" class="pointer-events-none select-none absolute right-[-0.05em] bottom-[-0.28em] leading-none font-display font-bold lowercase text-[190px] sm:text-[300px] lg:text-[380px] text-white/[0.045]">ai</div>
    <div class="relative z-10 max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-14">
        <div class="flex justify-between items-start gap-[60px] flex-wrap">
            <div class="max-w-[380px]">
                <img src="{{ asset('assets/logo-white.png') }}" alt="{{ $orgName }}" class="h-10 block mb-[22px] w-auto">
                <div class="text-sm leading-[1.6]">{!! nl2br(e($global->get('footer_address'))) !!}</div>
            </div>

            <div class="flex gap-[70px] text-[14.5px] leading-[2.1]">
                <div>
                    <div class="text-white font-semibold mb-1.5">{{ __('Съветът') }}</div>
                    <a href="{{ route($loc.'.about') }}" wire:navigate class="block hover:text-white">{{ $navLabel('about') }}</a>
                    <a href="{{ route($loc.'.positions') }}" wire:navigate class="block hover:text-white">{{ $navLabel('positions') }}</a>
                    <a href="{{ route($loc.'.news') }}" wire:navigate class="block hover:text-white">{{ $navLabel('news') }}</a>
                </div>
                <div>
                    <div class="text-white font-semibold mb-1.5">{{ __('Дейност') }}</div>
                    <a href="{{ route($loc.'.education') }}" wire:navigate class="block hover:text-white">{{ $navLabel('education') }}</a>
                    <a href="{{ route($loc.'.survey') }}" wire:navigate class="block hover:text-white">{{ $navLabel('survey') }}</a>
                    <a href="{{ route($loc.'.partners') }}" wire:navigate class="block hover:text-white">{{ $navLabel('partners') }}</a>
                </div>
            </div>

            <div class="max-w-[360px] flex-1 min-w-[280px]">
                <div class="text-white font-semibold text-[14.5px] mb-3">{{ $global->get('newsletter_title') }}</div>
                <p class="text-sm leading-[1.6] mb-3.5">{{ $global->get('newsletter_text') }}</p>
                <a href="{{ $newsletterUrl ?: 'https://www.bcci.bg' }}" target="_blank" rel="noopener"
                   class="inline-block bg-brand text-white px-5 py-3 font-semibold text-sm hover:bg-brand-dark">
                    {{ __('Абонирай ме') }}
                </a>
            </div>
        </div>

        <div class="border-t border-[#2A2B2F] mt-11 pt-[22px] text-[13px] flex justify-between flex-wrap gap-2.5">
            <span>{{ $global->get('copyright') }}</span>
            <a href="https://www.bcci.bg" target="_blank" rel="noopener" class="hover:text-white">bcci.bg</a>
        </div>
    </div>
</footer>
