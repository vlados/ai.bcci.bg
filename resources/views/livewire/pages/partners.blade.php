@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
        <p class="text-[16.5px] leading-[1.7] text-body mb-[34px] max-w-[760px]">{{ $page->get('intro') }}</p>
        @if ($partners->isNotEmpty())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-[26px]">
                @foreach ($partners as $partner)
                    @php $box = 'lift border border-line h-[130px] flex items-center justify-center bg-[#FBFAF8] p-4'; @endphp
                    @if ($partner->url)
                        <a href="{{ $partner->url }}" target="_blank" rel="noopener" class="{{ $box }} hover:border-ink">
                            @if ($partner->logo)
                                <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" class="max-h-[70px] max-w-full object-contain">
                            @else
                                <span class="text-[15px] font-semibold text-ink-soft text-center">{{ $partner->name }}</span>
                            @endif
                        </a>
                    @else
                        <div class="{{ $box }}">
                            @if ($partner->logo)
                                <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" class="max-h-[70px] max-w-full object-contain">
                            @else
                                <span class="text-[15px] font-semibold text-ink-soft text-center">{{ $partner->name }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-ink">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 flex justify-between items-center gap-8 flex-wrap">
            <div>
                <div class="text-[22px] lg:text-[26px] font-bold text-white mb-2.5 tracking-[-0.3px]">{{ $page->get('join_title') }}</div>
                <p class="text-[15.5px] text-[#A9AAAE]">{{ $page->get('join_text') }}</p>
            </div>
            <a href="{{ route($loc.'.contacts') }}" wire:navigate class="bg-brand text-white px-7 py-4 font-semibold text-[15.5px] whitespace-nowrap hover:bg-brand-dark">{{ $page->get('join_button') }}</a>
        </div>
    </div>
</div>
