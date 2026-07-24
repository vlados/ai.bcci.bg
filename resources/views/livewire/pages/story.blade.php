@php
    // Locale-aware number formatting: Bulgarian uses a decimal comma, English a
    // point. Trailing zeros are trimmed so 17.00 reads "17" but 8.55 stays "8,55".
    $num = function ($v, $max = 2) use ($loc) {
        [$dec, $th] = $loc === 'bg' ? [',', ' '] : ['.', ','];
        $s = number_format((float) $v, $max, $dec, $th);
        return str_contains($s, $dec) ? rtrim(rtrim($s, '0'), $dec) : $s;
    };
    $t = fn ($bg, $en) => $loc === 'bg' ? $bg : $en;

    // Headline gap: distance from the EU average, first year to last.
    $series = $adoption['series'];
    $firstYear = array_key_first($series);
    $lastYear = array_key_last($series);
    $gapFirst = round($series[$firstYear]['eu'] - $series[$firstYear]['bg'], 2);
    $gapLast = round($series[$lastYear]['eu'] - $series[$lastYear]['bg'], 2);

    // Line-chart geometry. The ONE deliberate exception to the 0–100 share-bar
    // rule above: a trend line must be readable to show divergence at all, so
    // its axis is zero-based 0–25 with labelled ticks (0/10/20). The bars on
    // this page all stay 0–100.
    $axis = 25;
    $vb = ['w' => 640, 'h' => 300, 'l' => 44, 'r' => 16, 't' => 20, 'b' => 34];
    $plotW = $vb['w'] - $vb['l'] - $vb['r'];
    $plotH = $vb['h'] - $vb['t'] - $vb['b'];
    $yearMin = $firstYear;
    $yearSpan = $lastYear - $firstYear;
    $px = fn ($year) => $vb['l'] + ($year - $yearMin) / $yearSpan * $plotW;
    $py = fn ($val) => $vb['t'] + (1 - min($val, $axis) / $axis) * $plotH;
    $pointsFor = function ($key) use ($series, $px, $py) {
        $pts = [];
        foreach ($series as $year => $v) {
            $pts[] = round($px($year), 1).','.round($py($v[$key]), 1);
        }
        return implode(' ', $pts);
    };
    $bgPoints = $pointsFor('bg');
    $euPoints = $pointsFor('eu');

    // Share bars fill their true fraction of a 0–100 track — the honesty rule
    // from config/eurostat.php. Never compress this axis to make a bar look
    // fuller; that is the visual lie the whole data file exists to prevent.
    $maxSize = 100;
@endphp
<div class="story" data-story>
    <div class="reading-rail" aria-hidden="true"><span class="reading-rail__fill"></span></div>

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <header class="st-hero" data-vt="hero">
        <div class="st-shell">
            <div class="st-kicker" data-enter><span class="dot"></span>{{ $t('Данни · Евростат 2025', 'Data · Eurostat 2025') }}</div>

            <div class="st-duel" data-enter>
                <div class="st-duel__side">
                    <div class="st-huge st-bg"><span data-count="{{ $series[$lastYear]['bg'] }}" data-suffix="%">{{ $num($series[$lastYear]['bg']) }}%</span></div>
                    <div class="st-duel__label st-bg">{{ $t('България', 'Bulgaria') }}</div>
                </div>
                <div class="st-duel__vs">{{ $t('срещу', 'vs') }}</div>
                <div class="st-duel__side">
                    <div class="st-huge st-eu"><span data-count="{{ $series[$lastYear]['eu'] }}" data-suffix="%">{{ $num($series[$lastYear]['eu']) }}%</span></div>
                    <div class="st-duel__label st-eu">{{ $t('ЕС-27', 'EU-27') }}</div>
                </div>
            </div>

            <h1 class="st-title" data-enter>{{ $t('Разликата, която се разширява', 'The gap that keeps widening') }}</h1>
            <p class="st-standfirst" data-enter>{{ $t(
                'През 2025 г. българските предприятия използват изкуствен интелект наполовина по-рядко от средното за ЕС. По-важното от самата разлика е нейната посока.',
                'In 2025, Bulgarian enterprises use artificial intelligence at half the EU rate. What matters more than the gap itself is its direction.'
            ) }}</p>

            <div class="st-scrollcue" data-enter aria-hidden="true">
                <span>{{ $t('Плъзнете надолу', 'Scroll') }}</span>
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M6 13l6 6 6-6"/></svg>
            </div>
        </div>
    </header>

    {{-- ── Beat 1 · the gap over time (scrubbed line draw) ──────────────── --}}
    <section class="st-beat" data-beat="line">
        <div class="st-shell">
            <div class="st-lead" data-enter>
                <h2 class="st-h2">{{ $t('Разликата не се затваря — разширява се', 'The gap is not closing — it is widening') }}</h2>
                <p class="st-prose">{{ $t(
                    "През $firstYear г. разстоянието до средното за ЕС беше ".$num($gapFirst)." процентни пункта. През $lastYear г. то е ".$num($gapLast).". За една година ЕС добавя 6,47 пункта; България — 2,08.",
                    "In $firstYear the distance to the EU average was ".$num($gapFirst)." percentage points. By $lastYear it is ".$num($gapLast).". In a single year the EU added 6.47 points; Bulgaria 2.08."
                ) }}</p>
            </div>

            <figure class="st-figure" data-enter>
                <div class="st-legend" aria-hidden="true">
                    <span class="st-legend__item st-bg"><span class="st-swatch"></span>{{ $t('България', 'Bulgaria') }}</span>
                    <span class="st-legend__item st-eu"><span class="st-swatch"></span>{{ $t('ЕС-27', 'EU-27') }}</span>
                    <span class="st-legend__axis">{{ $t('% от предприятията', '% of enterprises') }}</span>
                </div>

                <svg class="st-linechart" viewBox="0 0 {{ $vb['w'] }} {{ $vb['h'] }}" role="img"
                     aria-label="{{ $t('Линейна графика: делът на предприятията с ИИ в България и ЕС, '.$firstYear.'–'.$lastYear, 'Line chart: share of enterprises using AI, Bulgaria vs EU, '.$firstYear.'–'.$lastYear) }}">
                    {{-- gridlines + y ticks --}}
                    @foreach ([0, 10, 20] as $tick)
                        @php $gy = $vb['t'] + (1 - $tick / $axis) * $plotH; @endphp
                        <line x1="{{ $vb['l'] }}" y1="{{ $gy }}" x2="{{ $vb['w'] - $vb['r'] }}" y2="{{ $gy }}" class="st-grid"/>
                        <text x="{{ $vb['l'] - 8 }}" y="{{ $gy + 3.5 }}" class="st-tick" text-anchor="end">{{ $tick }}</text>
                    @endforeach
                    {{-- x year labels --}}
                    @foreach ($series as $year => $v)
                        <text x="{{ $px($year) }}" y="{{ $vb['h'] - 12 }}" class="st-tick" text-anchor="middle">{{ $year }}</text>
                    @endforeach
                    {{-- lines: rendered drawn (final state); JS sets the from-state --}}
                    <polyline data-line class="st-line st-line--eu" points="{{ $euPoints }}"/>
                    <polyline data-line class="st-line st-line--bg" points="{{ $bgPoints }}"/>
                    {{-- endpoint dots + value labels --}}
                    @foreach (['eu', 'bg'] as $key)
                        @php $lv = $series[$lastYear][$key]; @endphp
                        <circle data-dot cx="{{ round($px($lastYear), 1) }}" cy="{{ round($py($lv), 1) }}" r="4" class="st-dot st-dot--{{ $key }}"/>
                        <text data-dot cx x="{{ round($px($lastYear), 1) - 8 }}" y="{{ round($py($lv), 1) - 8 }}" class="st-val st-val--{{ $key }}" text-anchor="end">{{ $num($lv) }}%</text>
                    @endforeach
                </svg>
            </figure>

            <div class="st-gapcard" data-enter>
                <div class="st-gapcard__num"><span data-count="{{ $gapLast }}" data-from="{{ $gapFirst }}">{{ $num($gapLast) }}</span></div>
                <div class="st-gapcard__label">{{ $t('процентни пункта разлика през '.$lastYear.' г. — спрямо '.$num($gapFirst).' през '.$firstYear, 'percentage-point gap in '.$lastYear.' — up from '.$num($gapFirst).' in '.$firstYear) }}</div>
            </div>
        </div>
    </section>

    {{-- ── Beat 2 · not a size problem (grouped bars) ──────────────────── --}}
    <section class="st-beat st-beat--paper" data-beat="size">
        <div class="st-shell">
            <div class="st-lead" data-enter>
                <h2 class="st-h2">{{ $t('Не е въпрос на размер', 'Not a question of size') }}</h2>
                <p class="st-prose">{{ $t(
                    'Удобното обяснение е, че българската икономика е от малки фирми. Данните го опровергават: дори най-големите български предприятия остават под равнището на средните европейски.',
                    'The convenient explanation is that the Bulgarian economy is made of small firms. The data refute it: even Bulgaria’s largest enterprises sit below the rate of medium-sized European ones.'
                ) }}</p>
            </div>

            <div class="st-bars" data-enter>
                @foreach ($bySize as $band => $v)
                    <div class="st-bargroup">
                        <div class="st-bargroup__plot">
                            <div class="st-barcol">
                                <div class="st-bar st-bar--bg" data-bar style="--h: {{ round($v['bg'] / $maxSize * 100, 1) }}%">
                                    <span class="st-bar__val">{{ $num($v['bg']) }}%</span>
                                </div>
                            </div>
                            <div class="st-barcol">
                                <div class="st-bar st-bar--eu" data-bar style="--h: {{ round($v['eu'] / $maxSize * 100, 1) }}%">
                                    <span class="st-bar__val">{{ $num($v['eu']) }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="st-bargroup__label">{{ $t($band.' заети', $band.' employed') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Beat 3 · region isn't destiny (ranked bars) ─────────────────── --}}
    <section class="st-beat" data-beat="rank">
        <div class="st-shell">
            <div class="st-lead" data-enter>
                <h2 class="st-h2">{{ $t('Регионът не е съдба', 'The region is not destiny') }}</h2>
                <p class="st-prose">{{ $t(
                    'Словения е над средното за ЕС; Чехия и Словакия също изпреварват. България е трета от дъното — по-ниско са само Полша и Румъния.',
                    'Slovenia is above the EU average; Czechia and Slovakia lead too. Bulgaria is third from the bottom — only Poland and Romania rank lower.'
                ) }}</p>
            </div>

            <div class="st-rank" data-enter>
                @foreach ($ranking['countries'] as $c)
                    <div class="st-rankrow {{ $c['code'] === 'BG' ? 'is-focus' : '' }}">
                        <div class="st-rankrow__name">{{ $t($c['bg'], $c['en']) }}</div>
                        <div class="st-rankrow__track">
                            {{-- Width is the value itself on a 0–100 track, not scaled to the
                             leader: Denmark's 42% must fill 42% of the bar, not all of it. --}}
                        <div class="st-rankbar {{ $c['code'] === 'BG' ? 'st-bar--bg' : '' }}" data-bar-h style="--w: {{ round($c['value'], 1) }}%"></div>
                        </div>
                        <div class="st-rankrow__val">{{ $num($c['value']) }}%</div>
                    </div>
                @endforeach
                <div class="st-rank__note">{{ $t('Средно за ЕС-27: '.$num($ranking['avg']).'%', 'EU-27 average: '.$num($ranking['avg']).'%') }}</div>
            </div>
        </div>
    </section>

    {{-- ── Beat 4 · why they hold back (barriers) ──────────────────────── --}}
    <section class="st-beat st-beat--ink" data-beat="barriers">
        <div class="st-shell">
            <div class="st-lead" data-enter>
                <h2 class="st-h2 st-h2--light">{{ $t('Не защото ИИ е безполезен', 'Not because AI is useless') }}</h2>
                <p class="st-prose st-prose--light">{{ $t(
                    'Сред предприятията, които изобщо са обмисляли ИИ, водещите пречки са липса на експертиза и правна неяснота. „Не е полезен“ е най-малката причина.',
                    'Among enterprises that ever considered AI, the leading barriers are missing expertise and legal uncertainty. “Not useful” is the smallest reason of all.'
                ) }}</p>
            </div>

            <div class="st-barriers" data-enter>
                @foreach ($barriers['reasons'] as $r)
                    <div class="st-barrier {{ $loop->last ? 'is-muted' : '' }}">
                        <div class="st-barrier__label">{{ $t($r['bg_label'], $r['en_label']) }}</div>
                        <div class="st-barrier__track">
                            <div class="st-barrier__fill" data-bar-h style="--w: {{ round($r['bg'], 1) }}%"></div>
                        </div>
                        <div class="st-barrier__val">{{ $num($r['bg']) }}%</div>
                    </div>
                @endforeach
                <p class="st-barriers__foot">{{ $t(
                    'При това едва '.$num($barriers['considered']['bg']).'% от българските предприятия изобщо някога са обмисляли ИИ (при '.$num($barriers['considered']['eu']).'% за ЕС). Процентите по-горе са дял от тях.',
                    'And only '.$num($barriers['considered']['bg']).'% of Bulgarian enterprises ever considered AI at all ('.$num($barriers['considered']['eu']).'% in the EU). The shares above are of that group.'
                ) }}</p>
            </div>
        </div>
    </section>

    {{-- ── Beat 5 · employees are ahead ────────────────────────────────── --}}
    <section class="st-beat st-beat--paper" data-beat="people">
        <div class="st-shell">
            <div class="st-lead" data-enter>
                <h2 class="st-h2">{{ $t('Служителите изпреварват работодателите', 'Employees are ahead of employers') }}</h2>
            </div>

            <div class="st-duel st-duel--mid" data-enter>
                <div class="st-duel__side">
                    <div class="st-big st-bg"><span data-count="{{ $individuals['bg_16_74'] }}" data-suffix="%">{{ $num($individuals['bg_16_74']) }}%</span></div>
                    <div class="st-duel__label">{{ $t('лична употреба на генеративен ИИ', 'personal use of generative AI') }}</div>
                </div>
                <div class="st-duel__vs">{{ $t('срещу', 'vs') }}</div>
                <div class="st-duel__side">
                    <div class="st-big st-eu"><span data-count="{{ $individuals['bg_enterprise'] }}" data-suffix="%">{{ $num($individuals['bg_enterprise']) }}%</span></div>
                    <div class="st-duel__label">{{ $t('употреба във фирмите', 'enterprise use') }}</div>
                </div>
            </div>

            <p class="st-prose st-prose--center" data-enter>{{ $t(
                'Сред българите на 16–24 г. делът достига '.$num($individuals['bg_16_24']).'%. Хората вече използват тези инструменти; работодателите — още не.',
                'Among Bulgarians aged 16–24 it reaches '.$num($individuals['bg_16_24']).'%. People already use these tools; their employers do not yet.'
            ) }}</p>
        </div>
    </section>

    {{-- ── Close ───────────────────────────────────────────────────────── --}}
    <section class="st-close">
        <div class="st-shell">
            <div class="st-endmark" aria-hidden="true"></div>
            <p class="st-takeaway" data-enter>{{ $t(
                'Проблемът не е липса на технологични активи, а ограниченото им разпространение. Приоритетът е широко внедряване — умения, правна яснота и достъп до данни.',
                'The problem is not an absence of technological assets but their limited diffusion. The priority is broad adoption — skills, legal clarity and access to data.'
            ) }}</p>

            <div class="st-cta" data-enter>
                <a href="{{ route($loc.'.survey') }}" wire:navigate class="st-cta__primary">{{ $t('Към националното проучване', 'To the national survey') }} →</a>
                <a href="{{ route($loc.'.news') }}" wire:navigate class="st-cta__link">{{ $t('Пълните анализи', 'The full analyses') }}</a>
            </div>

            <p class="st-sources">{{ $t(
                'Източник: Евростат — isoc_eb_ai (използване на ИИ и пречки), isoc_ai_iaiu (лица). Предприятия с 10 и повече заети. Извлечено на '.\Illuminate\Support\Carbon::parse($adoption['extracted_on'])->translatedFormat('j F Y').'.',
                'Source: Eurostat — isoc_eb_ai (AI use and barriers), isoc_ai_iaiu (individuals). Enterprises with 10 or more persons employed. Extracted '.\Illuminate\Support\Carbon::parse($adoption['extracted_on'])->translatedFormat('j F Y').'.'
            ) }}</p>
        </div>
    </section>
</div>
