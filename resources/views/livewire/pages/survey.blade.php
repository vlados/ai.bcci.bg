@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] max-w-[900px] text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1.3fr_1fr] gap-8 lg:gap-14 items-start">
        <div class="bg-ink px-6 py-8 lg:px-11 lg:pt-11 lg:pb-12">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-[18px]">{{ $page->get('box_eyebrow') }}</div>
            <div class="text-[21px] lg:text-[27px] font-bold text-white mb-3.5 tracking-[-0.3px] text-pretty">{{ $page->get('box_title') }}</div>
            <p class="text-[15.5px] leading-[1.65] text-[#A9AAAE] mb-[30px]">{{ $page->get('box_text') }}</p>
            <a href="{{ $page->get('box_url') ?: '#' }}" target="_blank" rel="noopener" class="inline-block bg-brand text-white px-[30px] py-4 font-semibold text-base hover:bg-brand-dark">{{ $page->get('box_button') }}</a>
        </div>
        <div class="border border-line">
            <div class="px-7 py-5 border-b border-line text-[13.5px] font-bold tracking-[1.8px]">{{ $page->get('results_title') }}</div>
            @foreach ($page->list('results') as $r)
                <div class="px-7 py-[22px] text-[15.5px] text-ink-soft {{ ! $loop->last ? 'border-b border-line' : '' }}"><strong>{{ $r['label'] ?? '' }}</strong> — {{ $r['text'] ?? '' }}</div>
            @endforeach
        </div>
    </div>

    @if ($page->get('footer_note'))
        <div class="bg-paper border-t border-line">
            <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-11 flex justify-between items-center gap-6 flex-wrap">
                <span class="text-base text-ink-soft">{{ $page->get('footer_note') }}</span>
                <a href="{{ route($loc.'.news') }}" wire:navigate class="text-[15px] font-semibold text-brand whitespace-nowrap">{{ __('Към новините') }} →</a>
            </div>
        </div>
    @endif
</div>
