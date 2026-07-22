@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid gap-[26px]">
        @forelse ($articles as $article)
            <article data-morph-card class="reveal group lift border border-line grid lg:grid-cols-[320px_1fr]">
                <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block min-h-[220px] bg-[#E8E7E2] relative overflow-hidden">
                    @if ($article->imageUrl())
                        {{-- The first card is above the fold and is usually the LCP
                             element on this page, so it must not be lazy-loaded. --}}
                        <img data-morph src="{{ $article->imageUrl() }}" alt=""
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                             @if ($loop->first) fetchpriority="high" @endif decoding="async"
                             class="photo absolute inset-0 w-full h-full object-cover">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-[13px] text-[#9C9D9F]">{{ __('Изображение') }}</span>
                    @endif
                </a>
                <div class="px-5 py-6 lg:px-[34px] lg:pt-[30px] lg:pb-[34px]">
                    <div class="text-[13px] text-faint mb-2.5">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block text-[21px] font-bold leading-[1.35] mb-3 text-pretty hover:text-brand">{{ $article->tr('title') }}</a>
                    <p class="text-[15.5px] leading-[1.65] text-[#55565A] mb-4">{{ $article->tr('excerpt') }}</p>
                    {{-- Every card on the page would otherwise expose the same
                         anchor text, which is useless in a screen reader's link
                         list and gives search engines nothing to work with. --}}
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="text-[14.5px] font-semibold text-brand-dark">
                        {{ __('Прочетете') }}<span class="sr-only">: {{ $article->tr('title') }}</span> <span aria-hidden="true">→</span>
                    </a>
                </div>
            </article>
        @empty
            <p class="text-muted">{{ __('Очаквайте скоро.') }}</p>
        @endforelse

        <div class="mt-4">{{ $articles->links() }}</div>
    </div>
</div>
