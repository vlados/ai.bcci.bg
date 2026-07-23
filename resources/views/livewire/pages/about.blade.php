@php $loc = app()->getLocale(); @endphp
<div>
    <div data-vt="hero" class="bg-paper border-b border-line">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-sm font-bold tracking-widest text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4 max-w-4xl text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-relaxed text-body max-w-3xl">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight">{{ $page->get('goal_title') }}</h2>
        <div class="rich text-base text-body">{!! $page->get('goal_body') !!}</div>
    </div>

    <div class="bg-paper border-y border-line">
        <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-8">{{ $page->get('pillars_title') }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($page->list('pillars') as $p)
                    <div class="lift bg-white border border-line px-6 pt-6 pb-7 lg:px-8 lg:pt-8 lg:pb-8">
                        <div class="text-base font-bold text-brand mb-3.5">{{ $p['num'] ?? '' }}</div>
                        <div class="text-lg font-bold mb-3">{{ $p['title'] ?? '' }}</div>
                        <p class="text-base leading-relaxed text-hush">{{ $p['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if ($team->isNotEmpty())
        <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-2.5">{{ $page->get('team_title') }}</h2>
            <p class="text-base text-muted mb-8">{{ $page->get('team_subtitle') }}</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($team as $member)
                    <div class="group lift border border-line">
                        @if ($member->photo)
                            <img src="{{ asset('storage/'.$member->photo) }}" alt="" loading="lazy" decoding="async" class="photo h-75 w-full object-cover">
                        @else
                            <div class="h-75 bg-wash flex items-center justify-center text-sm text-faint">{{ __('Снимка') }}</div>
                        @endif
                        <div class="px-6 pt-5 pb-6">
                            <div class="text-lg font-bold">{{ $member->name }}</div>
                            <div class="text-sm text-brand font-semibold mt-1">{{ $member->tr('role') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-paper border-t border-line">
        <div class="reveal max-w-7xl mx-auto px-5 sm:px-8 py-12 lg:py-18 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
            <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-pretty">{{ $page->get('hub_title') }}</h2>
            <p class="text-base leading-relaxed text-body">{{ $page->get('hub_body') }}</p>
        </div>
    </div>
</div>
