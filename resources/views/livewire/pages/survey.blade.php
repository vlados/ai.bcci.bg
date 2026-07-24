@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 max-w-4xl text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1.3fr_1fr] gap-8 lg:gap-14 items-start">
        <div class="bg-ink px-6 py-8 lg:px-11 lg:pt-11 lg:pb-12">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('box_eyebrow') }}</div>
            <div class="text-xl lg:text-2xl font-bold text-white mb-3.5 tracking-tight text-pretty">{{ $page->get('box_title') }}</div>
            <p class="text-base leading-relaxed text-on-ink mb-8">{{ $page->get('box_text') }}</p>
            <a href="{{ $page->get('box_url') ?: '#' }}" target="_blank" rel="noopener"
               @if ($page->get('box_url')) data-track="survey_start" data-location="survey_page" @endif
               class="inline-block bg-brand text-white px-8 py-4 font-semibold text-base hover:bg-brand-dark">{{ $page->get('box_button') }}</a>
        </div>
        <div class="border border-line">
            <div class="px-7 py-5 border-b border-line text-sm font-bold tracking-widest">{{ $page->get('results_title') }}</div>
            @foreach ($page->list('results') as $r)
                <div class="px-7 py-5 text-base text-ink-soft {{ ! $loop->last ? 'border-b border-line' : '' }}"><strong>{{ $r['label'] ?? '' }}</strong> — {{ $r['text'] ?? '' }}</div>
            @endforeach
        </div>
    </div>

    @if ($page->get('footer_note'))
        <div class="bg-paper border-t border-line">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-11 flex justify-between items-center gap-6 flex-wrap">
                <span class="text-base text-ink-soft">{{ $page->get('footer_note') }}</span>
                <a href="{{ route($loc.'.news') }}" wire:navigate class="text-base font-semibold text-brand whitespace-nowrap">{{ __('Към новините') }} →</a>
            </div>
        </div>
    @endif
</div>
