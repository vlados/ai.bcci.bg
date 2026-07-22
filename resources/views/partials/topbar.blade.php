<div class="bg-ink text-[#B9BABE] text-[13.5px]">
    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-[9px] flex justify-between items-center gap-4">
        <span>{{ $global->get('topbar_tagline') }}</span>
        <span class="flex gap-3.5 items-center shrink-0">
            @foreach ($localeAlternates as $alt)
                @if ($alt['active'])
                    <span class="text-white font-semibold">{{ $alt['label'] }}</span>
                @else
                    <a href="{{ $alt['url'] }}" wire:navigate class="hover:text-white">{{ $alt['label'] }}</a>
                @endif
                @unless ($loop->last)
                    <span class="text-[#55565A]">|</span>
                @endunless
            @endforeach
        </span>
    </div>
</div>
