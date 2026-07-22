@php $orgName = config('site.org.name')[app()->getLocale()] ?? config('site.org.name')['bg']; @endphp
{{-- Escape and outside-click close the panel, and focus returns to the toggle
     so a keyboard user is not stranded inside a dismissed menu. --}}
<header data-vt="header"
        x-data="{ open: false, close() { if (this.open) { this.open = false; $refs.toggle?.focus() } } }"
        @keydown.escape.window="close()"
        @click.outside="open = false"
        class="border-b border-line bg-white sticky top-0 z-50">
    <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-3.5 flex justify-between items-center gap-x-6 gap-y-3">
        <a href="{{ route(app()->getLocale().'.home') }}" wire:navigate class="shrink-0">
            {{-- Intrinsic size of the asset (1800x234); CSS still sizes it. Present
                 purely so the browser can reserve the box and avoid layout shift. --}}
            <img src="{{ asset('assets/logo.png') }}" alt="{{ $orgName }}" width="1800" height="234"
                 fetchpriority="high" class="h-9 block w-auto">
        </a>

        <nav data-desknav class="hidden lg:flex items-center gap-x-4 text-sm font-medium whitespace-nowrap"
             aria-label="{{ __('Основна навигация') }}">
            @foreach ($nav as $item)
                @if ($item['key'] === 'contacts')
                    <a href="{{ $item['url'] }}" wire:navigate
                       class="bg-brand text-white px-4 py-2.5 font-semibold hover:bg-brand-dark">{{ $item['label'] }}</a>
                @else
                    <a href="{{ $item['url'] }}" wire:navigate
                       @if ($item['active']) aria-current="page" @endif
                       class="{{ $item['active'] ? 'text-brand' : 'text-ink-soft' }} hover:text-brand">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </nav>

        {{-- aria-expanded is also set statically: Alpine only binds it once it
             initialises, so without the literal attribute the button ships with
             no state at all for anything reading the raw markup. --}}
        <button type="button" x-ref="toggle" @click="open = !open"
                aria-expanded="false" :aria-expanded="open"
                aria-controls="mobile-nav" aria-label="{{ __('Меню') }}"
                class="lg:hidden cursor-pointer p-2 -mr-2">
            <span class="block w-6 h-0.5 bg-ink mb-1.5"></span>
            <span class="block w-6 h-0.5 bg-ink mb-1.5"></span>
            <span class="block w-6 h-0.5 bg-ink"></span>
        </button>
    </div>

    {{-- The active-page "ball": rides the top of the nav and bounces to the new
         item on navigation (driven by resources/js/app.js). Persisted across
         wire:navigate so it keeps its position to animate FROM. --}}
    @persist('navball')
        <span data-navball aria-hidden="true"
              class="hidden lg:block pointer-events-none absolute top-3.5 left-0 h-2.5 w-2.5 rounded-full bg-brand"
              style="transform: translateX(-9999px)"></span>
    @endpersist

    <nav id="mobile-nav" x-show="open" x-cloak class="lg:hidden border-t border-line bg-white" aria-label="{{ __('Меню') }}">
        @foreach ($nav as $item)
            <a href="{{ $item['url'] }}" wire:navigate @click="open = false"
               @if ($item['active']) aria-current="page" @endif
               class="block w-full text-left px-5 py-3.5 font-medium {{ $item['active'] ? 'text-brand' : 'text-ink-soft' }} {{ ! $loop->last ? 'border-b border-line' : '' }}">{{ $item['label'] }}</a>
        @endforeach
    </nav>
</header>
