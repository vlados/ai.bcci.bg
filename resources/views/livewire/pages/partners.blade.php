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
        <p class="text-base leading-relaxed text-body mb-8 max-w-3xl">{{ $page->get('intro') }}</p>
        @if ($partners->isNotEmpty())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($partners as $partner)
                    @php $box = 'lift border border-line h-32 flex items-center justify-center bg-paper-soft p-4'; @endphp
                    @if ($partner->url)
                        <a href="{{ $partner->url }}" target="_blank" rel="noopener" class="{{ $box }} hover:border-ink">
                            @if ($partner->logo)
                                <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" class="max-h-18 max-w-full object-contain">
                            @else
                                <span class="text-base font-semibold text-ink-soft text-center">{{ $partner->name }}</span>
                            @endif
                        </a>
                    @else
                        <div class="{{ $box }}">
                            @if ($partner->logo)
                                <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" loading="lazy" decoding="async" class="max-h-18 max-w-full object-contain">
                            @else
                                <span class="text-base font-semibold text-ink-soft text-center">{{ $partner->name }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-ink">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 flex justify-between items-center gap-8 flex-wrap">
            <div>
                <div class="text-xl lg:text-2xl font-bold text-white mb-2.5 tracking-tight">{{ $page->get('join_title') }}</div>
                <p class="text-base text-on-ink">{{ $page->get('join_text') }}</p>
            </div>
            <a href="{{ route($loc.'.contacts') }}" wire:navigate class="bg-brand text-white px-7 py-4 font-semibold text-base whitespace-nowrap hover:bg-brand-dark">{{ $page->get('join_button') }}</a>
        </div>
    </div>
</div>
