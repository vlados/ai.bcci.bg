@php
    $loc = app()->getLocale();
    $heroImage = $page->get('hero_image');
    $topics = collect(config('site.org.knows_about'))->map(fn ($t) => $t[$loc] ?? $t['bg'])->all();

    // Colour the final sentence of the hero headline red.
    $title = $page->get('hero_title');
    $splitAt = mb_strrpos($title, '. ');
    $titleHead = $splitAt !== false ? mb_substr($title, 0, $splitAt + 1) : $title;
    $titleTail = $splitAt !== false ? trim(mb_substr($title, $splitAt + 1)) : '';
@endphp
<div>
    {{-- Hero --}}
    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 pt-12 pb-12 lg:pt-[84px] lg:pb-[76px] grid lg:grid-cols-[1.25fr_1fr] gap-10 lg:gap-[72px]">
        <div>
            <div class="flex items-center gap-2.5 mb-[22px]">
                <span class="dot dot-pulse shrink-0"></span>
                <span class="text-[13.5px] font-bold tracking-[2.2px] text-brand">{{ $page->get('hero_eyebrow') }}</span>
            </div>
            <h1 class="uppercase text-[38px] sm:text-[56px] lg:text-[70px] leading-[1.02] font-bold text-ink mb-7 tracking-[-0.01em] text-pretty">{{ $titleHead }} @if ($titleTail)<span class="text-brand">{{ $titleTail }}</span>@endif</h1>
            <p class="text-lg leading-[1.65] text-body mb-9 max-w-[620px]">{{ $page->get('hero_intro') }}</p>
            <div class="flex gap-3.5 flex-wrap">
                <a href="{{ route($loc.'.survey') }}" wire:navigate class="bg-brand text-white px-[26px] py-[15px] font-semibold text-[15.5px] hover:bg-brand-dark">{{ $page->get('cta_primary') }}</a>
                <a href="{{ route($loc.'.about') }}" wire:navigate class="border-[1.5px] border-ink text-ink px-[26px] py-[15px] font-semibold text-[15.5px] hover:bg-ink hover:text-white">{{ $page->get('cta_secondary') }}</a>
            </div>
        </div>
        <div class="relative self-stretch">
            <div aria-hidden="true" class="hidden lg:block absolute -bottom-5 -right-5 w-44 h-44 border-[3px] border-brand"></div>
            <div class="relative h-full min-h-[260px] sm:min-h-[340px] bg-[#E8E7E2] overflow-hidden">
                @if ($heroImage)
                    {{-- This is the LCP element: eager + high priority, never lazy.
                         alt="" because the adjacent h1 already carries the meaning. --}}
                    <img src="{{ $heroImage }}" alt="" loading="eager" fetchpriority="high" decoding="async"
                         class="photo w-full h-full object-cover block">
                @endif
            </div>
        </div>
    </div>

    {{-- Topics ticker --}}
    @if (count($topics))
        <div class="ticker bg-ink text-white border-y border-[#2A2B2F]" aria-hidden="true">
            <div class="ticker-track py-3.5">
                @foreach (array_merge($topics, $topics) as $topic)
                    <span class="inline-flex items-center">
                        <span class="dot mx-5 shrink-0"></span>
                        <span class="text-[13px] font-semibold tracking-[2px] uppercase font-display whitespace-nowrap">{{ $topic }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Three pillars --}}
    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 pt-10 lg:pt-16 pb-10 lg:pb-16">
        <div class="border border-line">
            <div class="px-7 py-5 border-b border-line text-[13.5px] font-bold tracking-[1.8px]">{{ $page->get('pillars_title') }}</div>
            <div class="grid lg:grid-cols-3">
                @foreach ($page->list('pillars') as $p)
                    <div class="flex gap-5 px-7 py-6 {{ ! $loop->last ? 'border-b lg:border-b-0 lg:border-r border-line' : '' }}">
                        <span class="font-bold text-brand text-[15px]">{{ $p['num'] ?? '' }}</span>
                        <div>
                            <div class="font-semibold mb-[5px]">{{ $p['title'] ?? '' }}</div>
                            <div class="text-[14.5px] leading-[1.55] text-muted">{{ $p['text'] ?? '' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- People & technology --}}
    <div class="bg-paper border-y border-line">
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
            <h2 class="text-2xl lg:text-3xl font-bold tracking-[-0.3px] text-pretty">{{ $page->get('intro_title') }}</h2>
            <div class="grid gap-[18px]">
                <div class="rich text-[16.5px] text-body">{!! $page->get('intro_body') !!}</div>
                <a href="{{ route($loc.'.about') }}" wire:navigate class="text-[15px] font-semibold text-brand w-fit">{{ __('Повече за Съвета') }} →</a>
            </div>
        </div>
    </div>

    {{-- How the Council works --}}
    @if (count($page->list('process')))
        @php
            // Lucide line icons (inlined SVG), one per step: ask, connect, solve, share.
            $processIcons = [
                '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
                '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
                '<path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/>',
                '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
            ];
        @endphp
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 pt-10 lg:pt-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-[34px]">{{ $page->get('process_title') }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-[26px]">
                @foreach ($page->list('process') as $step)
                    @php $ghost = preg_replace('/\D/', '', (string) ($step['num'] ?? $loop->iteration)); @endphp
                    <div class="lift relative overflow-hidden border-t-4 {{ $loop->last ? 'border-brand' : 'border-ink' }} pt-5 pb-6 pr-3">
                        <span aria-hidden="true" class="pointer-events-none select-none absolute -right-1 -bottom-6 leading-none font-display font-bold text-[104px] text-ink/[0.06]">{{ $ghost }}</span>
                        <div class="relative">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-9 h-9 mb-4 {{ $loop->last ? 'text-brand' : 'text-ink' }}" aria-hidden="true">{!! $processIcons[$loop->index % count($processIcons)] !!}</svg>
                            <div class="text-[17px] font-bold mb-2">{{ $step['title'] ?? '' }}</div>
                            <p class="text-[14.5px] leading-[1.6] text-[#55565A]">{{ $step['text'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- AI adoption vs cloud adoption, Bulgaria against the EU-27.
         Real Eurostat figures on a shared 0-100% axis and an identical
         population, so the two panels can be read against each other.
         See config/eurostat.php for datasets, definitions and caveats. --}}
    @php
        $ai = config('eurostat.ai_adoption');
        $cloud = config('eurostat.cloud_adoption');
        $dii = config('eurostat.digital_intensity');
        $aiNow = $ai['series'][2025];
        $cloudNow = $cloud['series'][2025];
        $diiNow = $dii['series'][2024];
        // Two decimals throughout so the columns read evenly (17,50 not 17,5),
        // and a comma for Bulgarian, which is its decimal separator.
        $num = fn ($v) => number_format((float) $v, 2, $loc === 'bg' ? ',' : '.', '');
        // Only the years both indicators actually observe.
        $aiShared = array_intersect_key($ai['series'], $cloud['series']);
    @endphp
    <div class="reveal chart max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[0.85fr_1.15fr] gap-8 lg:gap-14 items-center">
        <div>
            <h2 class="text-2xl lg:text-3xl font-bold mb-4">{{ __('Проблемът не е само в изкуствения интелект') }}</h2>
            <p class="text-[16.5px] leading-[1.7] text-body mb-4">
                {{ __('През 2025 г. изкуствен интелект използват :bg% от българските предприятия с 10 и повече заети, при :eu% средно за ЕС. Но изоставането в облачните услуги — основата, върху която се гради работещ AI — е още по-голямо: :cbg% срещу :ceu%.', ['bg' => $num($aiNow['bg']), 'eu' => $num($aiNow['eu']), 'cbg' => $num($cloudNow['bg']), 'ceu' => $num($cloudNow['eu'])]) }}
            </p>
            <p class="text-[14.5px] leading-[1.6] text-muted mb-4">
                {{ __('Между 2023 и 2025 г. използването на облачни услуги в България се задържа на място, докато ЕС добавя над седем пункта. За сравнение, поне базово ниво на дигитализация имат :dbg% от българските и :deu% от европейските предприятия през 2024 г.', ['dbg' => $num($diiNow['bg']), 'deu' => $num($diiNow['eu'])]) }}
            </p>
            <p class="text-[14.5px] leading-[1.6] text-muted mb-6">
                {{ __('Националното проучване на Съвета ще покаже какво стои зад тези цифри — кои процеси, кои пречки и кои сектори.') }}
            </p>
            <a href="{{ route($loc.'.survey') }}" wire:navigate class="text-[15px] font-semibold text-brand-dark">{{ __('Към проучването') }} →</a>
        </div>
        <div>
            <div class="flex items-center gap-5 mb-5 text-[12.5px]">
                <span class="flex items-center gap-2"><span aria-hidden="true" class="w-3 h-3 bg-brand block"></span>{{ __('България') }}</span>
                <span class="flex items-center gap-2"><span aria-hidden="true" class="w-3 h-3 bg-eu block"></span>{{ __('ЕС-27') }}</span>
                <span class="text-faint">{{ __('% от предприятията') }}</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                @include('partials.bar-panel', [
                    'title' => __('Изкуствен интелект'),
                    'series' => $aiShared,
                    'caption' => __('Дял на предприятията с 10 и повече заети, използващи поне една технология на изкуствен интелект'),
                    'num' => $num,
                ])
                @include('partials.bar-panel', [
                    'title' => __('Облачни услуги'),
                    'series' => $cloud['series'],
                    'caption' => __('Дял на предприятията с 10 и повече заети, купуващи облачни услуги'),
                    'num' => $num,
                ])
            </div>

            <p class="text-[12.5px] text-faint mt-4 leading-[1.5]">
                {{ __('Източник: Евростат, :ai и :cloud — предприятия с 10+ заети, без селско стопанство, добив и финансов сектор. От 2025 г. показателят за AI обхваща осем технологии вместо седем.', ['ai' => $ai['dataset'], 'cloud' => $cloud['dataset']]) }}
                <a href="{{ $ai['source_url'] }}" rel="nofollow noopener" target="_blank" class="underline hover:text-ink">{{ __('Данните') }}</a>
            </p>
        </div>
    </div>

    {{-- Latest news --}}
    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
        <div class="flex justify-between items-baseline mb-[34px]">
            <h2 class="text-2xl lg:text-3xl font-bold">{{ $page->get('news_title') }}</h2>
            <a href="{{ route($loc.'.news') }}" wire:navigate class="text-[15px] font-semibold text-brand">{{ __('Всички новини') }} →</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-[26px]">
            @forelse ($news as $article)
                <a href="{{ route($loc.'.news.show', $article) }}" wire:navigate class="group lift border border-line block hover:border-ink">
                    <div class="h-[180px] overflow-hidden bg-[#E8E7E2]">
                        @if ($article->imageUrl())
                            <img src="{{ $article->imageUrl() }}" alt="" loading="lazy" decoding="async" class="photo w-full h-full object-cover block">
                        @endif
                    </div>
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

    {{-- Quote --}}
    @if ($page->get('quote_text'))
        <div class="bg-paper border-t border-line">
            <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-12 lg:py-[72px] text-center">
                <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-6">{{ $page->get('quote_eyebrow') }}</div>
                <p class="font-serif italic text-[21px] lg:text-[27px] leading-[1.5] max-w-[860px] mx-auto mb-[26px] text-pretty">„{{ $page->get('quote_text') }}“</p>
                <a href="{{ route($loc.'.positions') }}" wire:navigate class="inline-block border-b-2 border-brand pb-[3px] font-semibold text-[15.5px]">{{ __('Всички становища') }}</a>
            </div>
        </div>
    @endif
</div>
