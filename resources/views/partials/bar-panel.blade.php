{{--
    One small Bulgaria-vs-EU bar panel.

    The y-axis is always the full 0–100%, so a bar fills the share it actually
    represents. Never rescale it to "fit" the data: a bar that fills its track
    reads as "all of them" whatever number is printed underneath, which is the
    visual lie this chart was rebuilt to remove. That also means two panels can
    be read against each other — they share one scale.

    @param string $title
    @param array  $series   year => ['bg' => float, 'eu' => float]
    @param string $caption  accessible table caption
    @param callable $num    locale-aware number formatter
--}}
@php $ticks = [0, 25, 50, 75, 100]; @endphp

<div class="min-w-0">
    <h3 class="text-[13px] font-bold tracking-[1.4px] uppercase font-display mb-3">{{ $title }}</h3>

    <div aria-hidden="true" class="flex gap-2">
        <div class="relative w-8 shrink-0 h-[180px] lg:h-[210px] text-[10px] text-faint">
            @foreach ($ticks as $tick)
                <span class="absolute right-0 -translate-y-1/2 tabular-nums" style="top: {{ 100 - $tick }}%">{{ $tick }}</span>
            @endforeach
        </div>

        <div class="flex-1 min-w-0">
            <div class="relative h-[180px] lg:h-[210px] bg-[#F6F5F2]">
                @foreach ($ticks as $tick)
                    <div class="absolute left-0 right-0 border-t {{ $tick === 0 ? 'border-ink/25' : 'border-line' }}"
                         style="top: {{ 100 - $tick }}%"></div>
                @endforeach

                <div class="absolute inset-0 flex items-end gap-2 sm:gap-4 px-1">
                    @foreach ($series as $year => $v)
                        <div class="flex-1 h-full flex items-end justify-center gap-1">
                            <div class="w-1/2 max-w-[22px] h-full flex items-end">
                                <div class="bar w-full bg-brand" style="--h: {{ $v['bg'] }}%"></div>
                            </div>
                            <div class="w-1/2 max-w-[22px] h-full flex items-end">
                                <div class="bar w-full bg-eu" style="--h: {{ $v['eu'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Labels take the full half-column: unlike the bars they are not
                 width-capped, or five-character values like "40,97" collide. --}}
            <div class="flex gap-2 sm:gap-4 px-1 mt-1.5">
                @foreach ($series as $year => $v)
                    <div class="flex-1 min-w-0">
                        <div class="flex text-[10px] lg:text-[11px] font-semibold tabular-nums">
                            <span class="w-1/2 text-center text-brand-dark">{{ $num($v['bg']) }}</span>
                            <span class="w-1/2 text-center text-eu">{{ $num($v['eu']) }}</span>
                        </div>
                        <div class="text-center text-[11px] text-faint mt-0.5">{{ $year }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- sr-only goes on a wrapper, never on the <table> itself: a table cannot
         be laid out narrower than its min-content width, so `width: 1px` is
         ignored and the table stays full size — silently widening the page. --}}
    <div class="sr-only">
    <table>
        <caption>{{ $caption }}</caption>
        <thead>
            <tr>
                <th scope="col">{{ __('Година') }}</th>
                <th scope="col">{{ __('България') }}</th>
                <th scope="col">{{ __('ЕС-27') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($series as $year => $v)
                <tr>
                    <th scope="row">{{ $year }}</th>
                    <td>{{ $num($v['bg']) }}%</td>
                    <td>{{ $num($v['eu']) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
