@php $orgName = config('site.org.name')[app()->getLocale()] ?? config('site.org.name')['bg']; @endphp
<header class="border-b border-line bg-white sticky top-0 z-50">
    <div class="max-w-[1216px] mx-auto px-8 py-3.5 flex justify-between items-center gap-x-6 gap-y-3 flex-wrap">
        <a href="{{ route(app()->getLocale().'.home') }}" wire:navigate class="shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="{{ $orgName }}" class="h-9 block w-auto">
        </a>
        <nav class="flex items-center gap-x-4 gap-y-2 text-sm font-medium whitespace-nowrap flex-wrap justify-end"
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
    </div>
</header>
