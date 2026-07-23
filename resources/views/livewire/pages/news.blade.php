@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 grid gap-6">
        @forelse ($articles as $article)
            {{-- Without a cover image the card runs full width rather than
                 reserving 320px for an empty grey box. --}}
            <article data-morph-card class="reveal group lift border border-line grid {{ $article->imageUrl() ? 'lg:grid-cols-[320px_1fr]' : '' }}">
                @if ($article->imageUrl())
                <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block min-h-55 bg-wash relative overflow-hidden">
                        {{-- The first card is above the fold and is usually the LCP
                             element on this page, so it must not be lazy-loaded. --}}
                        <img data-morph src="{{ $article->imageUrl() }}" alt=""
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                             @if ($loop->first) fetchpriority="high" @endif decoding="async"
                             class="photo absolute inset-0 w-full h-full object-cover">
                </a>
                @endif
                <div class="px-5 py-6 lg:px-8 lg:pt-8 lg:pb-8">
                    <div class="text-sm text-faint mb-2.5">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block text-xl font-bold leading-snug mb-3 text-pretty hover:text-brand">{{ $article->tr('title') }}</a>
                    <p class="text-base leading-relaxed text-hush mb-4">{{ $article->tr('excerpt') }}</p>
                    {{-- Every card on the page would otherwise expose the same
                         anchor text, which is useless in a screen reader's link
                         list and gives search engines nothing to work with. --}}
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="text-sm font-semibold text-brand-dark">
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
