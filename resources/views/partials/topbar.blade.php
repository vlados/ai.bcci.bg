<div data-vt="topbar" class="bg-ink text-[#B9BABE] text-[13.5px]">
    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-[9px] flex justify-between items-center gap-4">
        <span>{{ $global->get('topbar_tagline') }}</span>
        {{-- lang/hreflang on each option so assistive tech announces "EN" in
             English rather than reading it with Bulgarian pronunciation, and
             so the switcher's purpose is exposed rather than inferred. --}}
        <nav aria-label="{{ __('Език') }}" class="flex gap-3.5 items-center shrink-0">
            @foreach ($localeAlternates as $alt)
                @if ($alt['active'])
                    <span lang="{{ $alt['locale'] }}" aria-current="true" class="text-white font-semibold">{{ $alt['label'] }}</span>
                @else
                    <a href="{{ $alt['url'] }}" wire:navigate
                       lang="{{ $alt['locale'] }}" hreflang="{{ $alt['locale'] }}"
                       class="hover:text-white">{{ $alt['label'] }}</a>
                @endif
                @unless ($loop->last)
                    <span aria-hidden="true" class="text-[#55565A]">|</span>
                @endunless
            @endforeach
        </nav>
    </div>
</div>
