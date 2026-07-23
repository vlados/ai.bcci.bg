@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-pretty">{{ $page->get('body_title') }}</h2>
        <div class="rich text-base text-body">{!! $page->get('body') !!}</div>
    </div>

    <div class="bg-paper border-t border-line">
        <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-8">{{ $page->get('plans_title') }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($page->list('plans') as $p)
                    <div class="lift bg-white border border-line px-6 pt-7 pb-8">
                        <div class="text-base font-bold text-brand mb-3">{{ $p['num'] ?? '' }}</div>
                        <div class="text-lg font-bold mb-2.5">{{ $p['title'] ?? '' }}</div>
                        <p class="text-sm leading-relaxed text-hush">{{ $p['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
            @if ($page->get('cta_text'))
                <div class="mt-10 border border-line bg-white px-8 py-6 flex justify-between items-center gap-6 flex-wrap">
                    <span class="text-base text-ink-soft">{{ $page->get('cta_text') }}</span>
                    <a href="{{ route($loc.'.contacts') }}" wire:navigate class="bg-brand text-white px-5 py-3 font-semibold text-sm whitespace-nowrap hover:bg-brand-dark">{{ $page->get('cta_button') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
