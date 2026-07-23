@php
    $loc = app()->getLocale();

    // Reading time from the body's word count. preg_split with /u so the
    // Cyrillic text is counted properly rather than by byte runs.
    $plain = trim(strip_tags($article->tr('body')));
    $words = $plain === '' ? 0 : count(preg_split('/\s+/u', $plain));
    $minutes = max(1, (int) ceil($words / 190));

    // Short name for the masthead kicker (one line on any width); the full name
    // signs the byline below, so the two are a hierarchy, not a repetition.
    $author = config('site.org.name')[$loc] ?? config('site.org.name')['bg'] ?? 'AI Council';
    $kicker = config('site.org.short')[$loc] ?? config('site.org.short')['bg'] ?? $author;

    $url = route($loc.'.news.show', $article);
    $shareX = 'https://twitter.com/intent/tweet?url='.urlencode($url).'&text='.urlencode($article->tr('title'));
    $shareIn = 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode($url);
@endphp
<div class="longform">
    {{-- Reading progress: a red hairline across the top of the viewport, driven
         by a CSS scroll timeline (no JS), echoing the social cards' top-bar. --}}
    <div class="reading-rail" aria-hidden="true"><span class="reading-rail__fill"></span></div>

    <article>
        {{-- Masthead. Stays a `hero` view-transition group so the card→article
             morph the site is built around still lands here. --}}
        <header data-vt="hero" class="bg-paper border-b border-line">
            <div class="lf-shell pt-9 pb-11 lg:pt-14 lg:pb-14">
                <a href="{{ route($loc.'.news') }}" wire:navigate class="lf-back lf-enter lf-enter-1">
                    <span aria-hidden="true">←</span> {{ __('Новини') }}
                </a>

                <div class="lf-kicker lf-enter lf-enter-2">
                    <span class="dot"></span>{{ $kicker }}
                </div>

                <h1 class="lf-title lf-enter lf-enter-3">{{ $article->tr('title') }}</h1>

                @if ($article->tr('excerpt'))
                    <p class="lf-standfirst lf-enter lf-enter-4">{{ $article->tr('excerpt') }}</p>
                @endif

                <div class="lf-meta lf-enter lf-enter-5">
                    @if ($article->published_at)
                        <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('j F Y') }}</time>
                        <span class="lf-meta__sep" aria-hidden="true"></span>
                    @endif
                    <span>{{ $minutes }} {{ __('мин четене') }}</span>
                </div>

                <div class="lf-rule lf-enter lf-enter-6" aria-hidden="true"></div>
            </div>
        </header>

        @if ($article->imageUrl())
            {{-- Breaks out past the reading measure to the full shell. The fixed
                 aspect ratio reserves the box so nothing shifts as it decodes,
                 and gives the view transition a stable target to morph into. --}}
            <figure class="lf-figure">
                <img src="{{ $article->coverSrc() }}" alt="" loading="eager" fetchpriority="high" decoding="async"
                     {{-- Breaks out to --lf-break (56rem / 896px) on desktop. --}}
                     @if ($set = $article->coverSrcset())
                         srcset="{{ $set }}"
                         sizes="(min-width: 896px) 896px, calc(100vw - 2.5rem)"
                     @endif
                     style="view-transition-name: article-hero">
            </figure>
        @endif

        <div class="lf-shell pt-10 lg:pt-14 pb-14">
            <div class="rich lf-body">{!! $article->tr('body') !!}</div>

            <div class="lf-endmark" aria-hidden="true"></div>

            <footer class="lf-byline">
                <div>
                    <div class="lf-byline__label">{{ __('Публикувано от') }}</div>
                    <div class="lf-byline__name">{{ $author }}</div>
                </div>
                <div class="lf-share">
                    <span class="lf-share__label">{{ __('Споделете') }}</span>
                    <a href="{{ $shareX }}" target="_blank" rel="noopener noreferrer" aria-label="X">X</a>
                    <a href="{{ $shareIn }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">in</a>
                </div>
            </footer>
        </div>
    </article>

    @if ($more->isNotEmpty())
        <div class="bg-paper border-t border-line">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
                <div class="flex items-baseline justify-between mb-8">
                    <h2 class="text-2xl font-bold">{{ __('Още новини') }}</h2>
                    <a href="{{ route($loc.'.news') }}" wire:navigate class="text-sm font-semibold text-brand">{{ __('Всички новини') }} →</a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($more as $item)
                        <a href="{{ route($loc.'.news.show', $item) }}" wire:navigate class="group lift border border-line bg-white block hover:border-ink">
                            <div class="h-45 overflow-hidden bg-wash">
                                @if ($item->imageUrl())
                                    <img src="{{ $item->coverSrc() }}" alt="{{ $item->tr('title') }}" loading="lazy"
                                         @if ($set = $item->coverSrcset())
                                             srcset="{{ $set }}" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                                         @endif
                                         class="w-full h-full object-cover block">
                                @endif
                            </div>
                            <div class="px-6 pt-6 pb-7">
                                <div class="text-sm text-faint mb-2.5">{{ $item->published_at?->translatedFormat('j F Y') }}</div>
                                <div class="text-lg font-semibold leading-snug">{{ $item->tr('title') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
