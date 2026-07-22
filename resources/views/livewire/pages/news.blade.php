@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-8 py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[42px] font-bold tracking-[-0.5px] mb-[18px] leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="max-w-[1216px] mx-auto px-8 py-16 grid gap-[26px]">
        @forelse ($articles as $article)
            <article class="border border-line grid lg:grid-cols-[320px_1fr]">
                <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block">
                    @if ($article->image)
                        <img src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->tr('title') }}" class="h-full min-h-[220px] w-full object-cover">
                    @else
                        <div class="min-h-[220px] h-full bg-[#E8E7E2] flex items-center justify-center text-[13px] text-[#9C9D9F]">{{ __('Изображение') }}</div>
                    @endif
                </a>
                <div class="px-[34px] pt-[30px] pb-[34px]">
                    <div class="text-[13px] text-faint mb-2.5">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="block text-[21px] font-bold leading-[1.35] mb-3 text-pretty hover:text-brand">{{ $article->tr('title') }}</a>
                    <p class="text-[15.5px] leading-[1.65] text-[#55565A] mb-4">{{ $article->tr('excerpt') }}</p>
                    <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="text-[14.5px] font-semibold text-brand">{{ __('Прочетете') }} →</a>
                </div>
            </article>
        @empty
            <p class="text-muted">{{ __('Очаквайте скоро.') }}</p>
        @endforelse

        <div class="mt-4">{{ $articles->links() }}</div>
    </div>
</div>
