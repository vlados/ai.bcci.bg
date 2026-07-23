@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
        <div class="rich text-base text-body max-w-4xl">{!! $page->get('body') !!}</div>
    </div>

    <div class="bg-paper border-t border-line">
        <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-8">{{ $page->get('list_title') }}</h2>
            @if ($positions->isNotEmpty())
                <div class="bg-white border border-line">
                    @foreach ($positions as $p)
                        <div class="flex gap-4 lg:gap-7 px-5 lg:px-8 py-5 items-center flex-wrap hover:bg-paper-soft {{ ! $loop->last ? 'border-b border-line' : '' }}">
                            <span class="text-sm text-faint w-24 shrink-0">{{ $p->document_date?->format('d.m.Y') }}</span>
                            @if ($p->scope === 'eu')
                                <span class="text-xs font-bold tracking-widest text-brand border border-brand px-2.5 py-1 shrink-0">{{ $p->badgeLabel() }}</span>
                            @else
                                <span class="text-xs font-bold tracking-widest text-hush border border-line-strong px-2.5 py-1 shrink-0">{{ $p->badgeLabel() }}</span>
                            @endif
                            <span class="text-base font-semibold flex-1 leading-normal min-w-60">{{ $p->tr('title') }}</span>
                            @if ($p->pdf_path)
                                <a href="{{ asset('storage/'.$p->pdf_path) }}" target="_blank" rel="noopener" class="text-sm font-bold text-white bg-ink px-3.5 py-2 shrink-0 hover:bg-brand">PDF ↓</a>
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
