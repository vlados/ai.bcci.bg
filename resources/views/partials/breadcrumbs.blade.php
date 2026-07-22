@php
    // Same array the BreadcrumbList JSON-LD is built from, so the visible trail
    // and the structured data can never disagree. A single-item trail is the
    // homepage, which has no path worth showing.
    $trail = app(\App\Support\Seo::class)->trail();
@endphp

@if (count($trail) > 1)
    <nav aria-label="{{ __('Навигация') }}" class="border-b border-line bg-white">
        <ol class="max-w-[1216px] mx-auto px-5 sm:px-8 py-3 flex flex-wrap items-center gap-x-2 gap-y-1 text-[13px] text-muted">
            @foreach ($trail as $crumb)
                <li class="flex items-center gap-2 min-w-0">
                    @if (! $loop->last && ! empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}" wire:navigate class="hover:text-brand-dark underline-offset-2 hover:underline">{{ $crumb['name'] }}</a>
                        <span aria-hidden="true" class="text-line">/</span>
                    @else
                        {{-- Current page: not a link, and marked as the current location. --}}
                        <span aria-current="page" class="text-ink-soft truncate max-w-[60vw] sm:max-w-none">{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
