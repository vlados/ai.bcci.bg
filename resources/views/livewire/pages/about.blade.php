@php $loc = app()->getLocale(); @endphp
<div>
    <div class="bg-paper border-b border-line">
        <div class="max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">{{ $page->get('hero_eyebrow') }}</div>
            <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] max-w-[900px] text-pretty leading-tight">{{ $page->get('hero_title') }}</h1>
            <p class="text-lg leading-[1.65] text-body max-w-[820px]">{{ $page->get('hero_intro') }}</p>
        </div>
    </div>

    <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16 grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-[-0.3px]">{{ $page->get('goal_title') }}</h2>
        <div class="rich text-[16.5px] text-body">{!! $page->get('goal_body') !!}</div>
    </div>

    <div class="bg-paper border-y border-line">
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-[34px]">{{ $page->get('pillars_title') }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-[26px]">
                @foreach ($page->list('pillars') as $p)
                    <div class="lift bg-white border border-line px-6 pt-6 pb-7 lg:px-[30px] lg:pt-[30px] lg:pb-[34px]">
                        <div class="text-[15px] font-bold text-brand mb-3.5">{{ $p['num'] ?? '' }}</div>
                        <div class="text-lg font-bold mb-3">{{ $p['title'] ?? '' }}</div>
                        <p class="text-[15px] leading-[1.65] text-[#55565A]">{{ $p['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if ($team->isNotEmpty())
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-10 lg:py-16">
            <h2 class="text-2xl lg:text-3xl font-bold mb-2.5">{{ $page->get('team_title') }}</h2>
            <p class="text-[15.5px] text-muted mb-[34px]">{{ $page->get('team_subtitle') }}</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-[26px]">
                @foreach ($team as $member)
                    <div class="group lift border border-line">
                        @if ($member->photo)
                            <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" class="photo h-[300px] w-full object-cover">
                        @else
                            <div class="h-[300px] bg-[#E8E7E2] flex items-center justify-center text-[13px] text-[#9C9D9F]">{{ __('Снимка') }}</div>
                        @endif
                        <div class="px-6 pt-5 pb-6">
                            <div class="text-[17px] font-bold">{{ $member->name }}</div>
                            <div class="text-sm text-brand font-semibold mt-1">{{ $member->tr('role') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-paper border-t border-line">
        <div class="reveal max-w-[1216px] mx-auto px-5 sm:px-8 py-12 lg:py-[72px] grid lg:grid-cols-[1fr_1.6fr] gap-8 lg:gap-16">
            <h2 class="text-2xl lg:text-3xl font-bold tracking-[-0.3px] text-pretty">{{ $page->get('hub_title') }}</h2>
            <p class="text-[16.5px] leading-[1.7] text-body">{{ $page->get('hub_body') }}</p>
        </div>
    </div>
</div>
