@php $loc = app()->getLocale(); @endphp
<div>
    <div class="max-w-[1216px] mx-auto px-8 pt-[84px] pb-[76px] grid lg:grid-cols-[1.25fr_1fr] gap-[72px]">
        <div>
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-[22px]">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[52px] leading-[1.12] font-bold text-ink mb-[26px] tracking-[-0.5px] text-pretty">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body mb-9 max-w-[620px]">{{ $page->get('hero_intro') }}</p>
            <div class="flex gap-3.5 flex-wrap">
                <a href="{{ route($loc.'.survey') }}" wire:navigate class="bg-brand text-white px-[26px] py-[15px] font-semibold text-[15.5px] hover:bg-brand-dark">{{ $page->get('cta_primary') }}</a>
                <a href="{{ route($loc.'.about') }}" wire:navigate class="border-[1.5px] border-ink text-ink px-[26px] py-[15px] font-semibold text-[15.5px] hover:bg-ink hover:text-white">{{ $page->get('cta_secondary') }}</a>
            </div>
        </div>
        <div class="border border-line self-start">
            <div class="px-7 py-5 border-b border-line text-[13.5px] font-bold tracking-[1.8px]">{{ $page->get('pillars_title') }}</div>
            @foreach ($page->list('pillars') as $p)
                <div class="flex gap-5 px-7 py-6 {{ ! $loop->last ? 'border-b border-line' : '' }}">
                    <span class="font-bold text-brand text-[15px]">{{ $p['num'] ?? '' }}</span>
                    <div>
                        <div class="font-semibold mb-[5px]">{{ $p['title'] ?? '' }}</div>
                        <div class="text-[14.5px] leading-[1.55] text-muted">{{ $p['text'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-paper border-y border-line">
        <div class="max-w-[1216px] mx-auto px-8 py-16 grid lg:grid-cols-[1fr_1.6fr] gap-16">
            <h2 class="text-3xl font-bold tracking-[-0.3px] text-pretty">{{ $page->get('intro_title') }}</h2>
            <div class="grid gap-[18px]">
                <div class="rich text-[16.5px] text-body">{!! $page->get('intro_body') !!}</div>
                <a href="{{ route($loc.'.about') }}" wire:navigate class="text-[15px] font-semibold text-brand w-fit">{{ __('Повече за Съвета') }} →</a>
            </div>
        </div>
    </div>

    <div class="max-w-[1216px] mx-auto px-8 py-16">
        <div class="flex justify-between items-baseline mb-[34px]">
            <h2 class="text-3xl font-bold">{{ $page->get('news_title') }}</h2>
            <a href="{{ route($loc.'.news') }}" wire:navigate class="text-[15px] font-semibold text-brand">{{ __('Всички новини') }} →</a>
        </div>
        <div class="grid lg:grid-cols-3 gap-[26px]">
            @forelse ($news as $article)
                <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="border border-line block hover:border-ink">
                    @if ($article->image)
                        <img src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->tr('title') }}" class="h-[180px] w-full object-cover">
                    @else
                        <div class="h-[180px] bg-[#E8E7E2] flex items-center justify-center text-[13px] text-[#9C9D9F]">{{ __('Изображение') }}</div>
                    @endif
                    <div class="px-[26px] pt-6 pb-7">
                        <div class="text-[13px] text-faint mb-2.5">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
                        <div class="text-[17px] font-semibold leading-[1.4]">{{ $article->tr('title') }}</div>
                    </div>
                </a>
            @empty
                <p class="text-muted">{{ __('Очаквайте скоро.') }}</p>
            @endforelse
        </div>
    </div>

    @if ($page->get('quote_text'))
        <div class="bg-paper border-t border-line">
            <div class="max-w-[1216px] mx-auto px-8 py-[72px] text-center">
                <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-6">{{ $page->get('quote_eyebrow') }}</div>
                <p class="font-serif italic text-[27px] leading-[1.5] max-w-[860px] mx-auto mb-[26px] text-pretty">„{{ $page->get('quote_text') }}“</p>
                <a href="{{ route($loc.'.positions') }}" wire:navigate class="inline-block border-b-2 border-brand pb-[3px] font-semibold text-[15.5px]">{{ __('Всички становища') }}</a>
            </div>
        </div>
    @endif
</div>
