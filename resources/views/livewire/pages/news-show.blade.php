@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[820px] mx-auto px-8 py-16">
            <a href="{{ route($loc.'.news') }}" wire:navigate class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4 inline-block">← {{ __('Новини') }}</a>
            <div class="text-[13px] text-faint mb-3">{{ $article->published_at?->translatedFormat('j F Y') }}</div>
            <h1 class="text-[38px] font-bold tracking-[-0.5px] leading-tight text-pretty">{{ $article->tr('title') }}</h1>
        </div>
    </div>

    <article class="max-w-[820px] mx-auto px-8 py-16">
        @if ($article->image)
            <img src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->tr('title') }}" class="w-full mb-10 border border-line">
        @endif
        @if ($article->tr('excerpt'))
            <p class="text-[19px] leading-[1.6] text-ink-soft font-medium mb-8">{{ $article->tr('excerpt') }}</p>
        @endif
        <div class="rich text-[17px] text-body leading-[1.75]">{!! $article->tr('body') !!}</div>
    </article>

    @if ($more->isNotEmpty())
        <div class="bg-paper border-t border-line">
            <div class="max-w-[1216px] mx-auto px-8 py-16">
                <h2 class="text-2xl font-bold mb-[34px]">{{ __('Още новини') }}</h2>
                <div class="grid lg:grid-cols-3 gap-[26px]">
                    @foreach ($more as $item)
                        <a href="{{ route($loc.'.news.show', $item) }}" wire:navigate class="border border-line bg-white block hover:border-ink">
                            @if ($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->tr('title') }}" class="h-[180px] w-full object-cover">
                            @else
                                <div class="h-[180px] bg-[#E8E7E2] flex items-center justify-center text-[13px] text-[#9C9D9F]">{{ __('Изображение') }}</div>
                            @endif
                            <div class="px-[26px] pt-6 pb-7">
                                <div class="text-[13px] text-faint mb-2.5">{{ $item->published_at?->translatedFormat('j F Y') }}</div>
                                <div class="text-[17px] font-semibold leading-[1.4]">{{ $item->tr('title') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
