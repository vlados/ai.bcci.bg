@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-[-0.3px] text-pretty">{{ $page->get('body_title') }}</h2>
        <div class="rich text-[16.5px] text-body">{!! $page->get('body') !!}</div>
    </div>

    <div class="bg-paper border-t border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-[34px]">{{ $page->get('plans_title') }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-[26px]">
                @foreach ($page->list('plans') as $p)
                    <div class="bg-white border border-line px-[26px] pt-7 pb-8">
                        <div class="text-[15px] font-bold text-brand mb-3">{{ $p['num'] ?? '' }}</div>
                        <div class="text-[17px] font-bold mb-2.5">{{ $p['title'] ?? '' }}</div>
                        <p class="text-[14.5px] leading-[1.6] text-[#55565A]">{{ $p['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
            @if ($page->get('cta_text'))
                <div class="mt-10 border border-line bg-white px-[30px] py-[26px] flex justify-between items-center gap-6 flex-wrap">
                    <span class="text-base text-ink-soft">{{ $page->get('cta_text') }}</span>
                    <a href="{{ route($loc.'.contacts') }}" wire:navigate class="bg-brand text-white px-[22px] py-[13px] font-semibold text-[14.5px] whitespace-nowrap hover:bg-brand-dark">{{ $page->get('cta_button') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
