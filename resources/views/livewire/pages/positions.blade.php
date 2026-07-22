@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
        <div class="rich text-[16.5px] text-body max-w-[900px]">{!! $page->get('body') !!}</div>
    </div>

    <div class="bg-paper border-t border-line">
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-[34px]">{{ $page->get('list_title') }}</h2>
            @if ($positions->isNotEmpty())
                <div class="bg-white border border-line">
                    @foreach ($positions as $p)
                        <div class="flex gap-4 lg:gap-7 px-5 lg:px-[30px] py-5 items-center flex-wrap hover:bg-[#FBFAF8] {{ ! $loop->last ? 'border-b border-line' : '' }}">
                            <span class="text-sm text-faint w-24 shrink-0">{{ $p->document_date?->format('d.m.Y') }}</span>
                            @if ($p->scope === 'eu')
                                <span class="text-[12.5px] font-bold tracking-[1px] text-brand border border-brand px-2.5 py-1 shrink-0">{{ $p->badgeLabel() }}</span>
                            @else
                                <span class="text-[12.5px] font-bold tracking-[1px] text-[#55565A] border border-[#C9C8C3] px-2.5 py-1 shrink-0">{{ $p->badgeLabel() }}</span>
                            @endif
                            <span class="text-[16.5px] font-semibold flex-1 leading-[1.45] min-w-[240px]">{{ $p->tr('title') }}</span>
                            @if ($p->pdf_path)
                                <a href="{{ asset('storage/'.$p->pdf_path) }}" target="_blank" rel="noopener" class="text-[13.5px] font-bold text-white bg-ink px-3.5 py-2 shrink-0 hover:bg-brand">PDF ↓</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">{{ __('Очаквайте скоро.') }}</p>
            @endif
        </div>
    </div>
</div>
