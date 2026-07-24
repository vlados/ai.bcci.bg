<?php

/*
|--------------------------------------------------------------------------
| Eurostat reference data
|--------------------------------------------------------------------------
|
| Official figures used in public-facing charts and copy. Kept here rather
| than inline in a template so that every published number has its dataset,
| population definition and extraction date attached to it.
|
| RULE: nothing in this file may be estimated, smoothed, interpolated or
| rounded for visual effect. If a value is not in the source dataset, it does
| not belong here. The site previously shipped an invented "illustrative"
| adoption curve; that is the failure this file exists to prevent.
|
| Verified against the source on 22 July 2026.
|
*/

return [

    /*
     | Share of enterprises using at least one AI technology.
     |
     | Dataset:    isoc_eb_ai (indicator E_AI_TANY, unit PC_ENT)
     | Population: enterprises with 10 or more persons employed,
     |             NACE Rev. 2 C10-S951_X_K — all activities except
     |             agriculture/forestry/fishing and mining/quarrying, and
     |             excluding the financial sector.
     | Note:       there is NO 2022 observation; do not interpolate one.
     | Caveat:     the indicator covered seven AI technologies up to 2024 and
     |             eight from 2025 (image/video/audio generation was added), so
     |             part of the 2024→2025 rise reflects the wider definition.
     */
    'ai_adoption' => [
        'dataset' => 'isoc_eb_ai',
        'indicator' => 'E_AI_TANY',
        'source_url' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table',
        'extracted_on' => '2026-07-22',

        // year => [Bulgaria, EU-27]
        'series' => [
            2021 => ['bg' => 3.29, 'eu' => 7.65],
            2023 => ['bg' => 3.62, 'eu' => 8.06],
            2024 => ['bg' => 6.47, 'eu' => 13.48],
            2025 => ['bg' => 8.55, 'eu' => 19.95],
        ],

        /*
         | Top of the y-axis, in percentage points.
         |
         | This is 100 and must stay 100. These are shares of a population, so a
         | bar has to fill the fraction of the track that it actually represents:
         | 8.55% fills 8.55% of the column. Compressing the axis to ~20 made the
         | EU bar fill its whole track, which reads as "all of them" at a glance
         | however the number underneath is labelled — the same kind of visual
         | lie as the invented curve this chart replaced.
         */
        'axis_max' => 100,
    ],

    /*
     | Share of enterprises buying paid cloud computing services.
     |
     | Dataset:    isoc_cicce_use (indicator E_CC, unit PC_ENT)
     | Population: IDENTICAL to the AI series above — 10+ persons employed,
     |             NACE Rev. 2 C10-S951_X_K. That is what makes the two
     |             directly comparable; do not swap in a different size class.
     | Note:       observations exist for 2021, 2023 and 2025, which line up
     |             with three of the four AI years. There is no 2024 cloud
     |             observation, so both series are shown for 2021/2023/2025.
     |
     | Included because it tests the obvious explanation for the AI gap. It
     | does not support it: Bulgaria is further behind on cloud (34% of the EU
     | level in 2025) than on AI (43%), and its cloud adoption barely moved
     | between 2023 and 2025 while the EU gained more than seven points.
     */
    'cloud_adoption' => [
        'dataset' => 'isoc_cicce_use',
        'indicator' => 'E_CC',
        'source_url' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_cicce_use/default/table',
        'extracted_on' => '2026-07-22',

        'series' => [
            2021 => ['bg' => 12.79, 'eu' => 40.97],
            2023 => ['bg' => 17.50, 'eu' => 45.32],
            2025 => ['bg' => 17.83, 'eu' => 52.74],
        ],
    ],

    /*
     | Enterprises with at least a basic level of digital intensity.
     |
     | Dataset:    isoc_e_dii (indicator E_DI3_GELO, unit PC_ENT, GE10,
     |             NACE C10-S951_X_K — the same population as above)
     |
     | !! METHODOLOGY TRAP — READ BEFORE EDITING !!
     | Eurostat publishes TWO digital-intensity variants on alternating years:
     |
     |     E_DI3_GELO → 2021, 2023, 2025   (BG 26.09 / 29.36 / 39.35)
     |     E_DI4_GELO → 2022, 2024         (BG 48.24 / 50.94)
     |
     | They use different criteria and produce very different levels for the
     | same country. Mixing them into one series — or comparing a value from
     | one against a value from the other — invents a trend that does not
     | exist. We use E_DI3 throughout because its years line up exactly with
     | the AI and cloud series. E_DI4 values are recorded here only so nobody
     | "helpfully" fills the 2022/2024 gaps with them.
     |
     | Cross-check: Eurostat's own Digitalisation in Europe 2026 publication
     | reports "72% of EU businesses reached a basic level of digital
     | intensity" for 2025, matching E_DI3_GELO EU27 = 72.11.
     */
    'digital_intensity' => [
        'dataset' => 'isoc_e_dii',
        'indicator' => 'E_DI3_GELO',
        'source_url' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_e_dii/default/table',
        'extracted_on' => '2026-07-22',

        'series' => [
            2021 => ['bg' => 26.09, 'eu' => 55.75],
            2023 => ['bg' => 29.36, 'eu' => 58.87],
            2025 => ['bg' => 39.35, 'eu' => 72.11],
        ],

        // Do NOT plot these against the series above. Different methodology.
        'alternate_methodology_e_di4' => [
            2022 => ['bg' => 48.24, 'eu' => 69.82],
            2024 => ['bg' => 50.94, 'eu' => 73.65],
        ],
    ],

    /*
     | 2025 adoption by enterprise size — the same dataset, size classes
     | 10-49 / 50-249 / 250+. Included because it refutes the common
     | explanation that Bulgaria's gap is purely a company-size effect.
     */
    'ai_adoption_by_size' => [
        '10-49' => ['bg' => 7.17, 'eu' => 17.00],
        '50-249' => ['bg' => 13.30, 'eu' => 30.36],
        '250+' => ['bg' => 26.18, 'eu' => 55.03],
    ],

    /*
     | 2025 adoption ranked across Member States — same dataset (isoc_eb_ai,
     | E_AI_TANY, 10+ employed). Included because it shows the region is not
     | destiny: Slovenia sits above the EU average while Bulgaria is third from
     | bottom. `avg` is the EU-27 figure, carried for the reference line.
     |
     | Ordered high to low, exactly as published — do not resort for effect.
     */
    'ai_adoption_ranking' => [
        'avg' => 19.95,
        'countries' => [
            ['code' => 'DK', 'bg' => 'Дания', 'en' => 'Denmark', 'value' => 42.03],
            ['code' => 'FI', 'bg' => 'Финландия', 'en' => 'Finland', 'value' => 37.82],
            ['code' => 'SE', 'bg' => 'Швеция', 'en' => 'Sweden', 'value' => 35.04],
            ['code' => 'SI', 'bg' => 'Словения', 'en' => 'Slovenia', 'value' => 21.61],
            ['code' => 'SK', 'bg' => 'Словакия', 'en' => 'Slovakia', 'value' => 18.00],
            ['code' => 'CZ', 'bg' => 'Чехия', 'en' => 'Czechia', 'value' => 17.60],
            ['code' => 'HR', 'bg' => 'Хърватия', 'en' => 'Croatia', 'value' => 15.19],
            ['code' => 'HU', 'bg' => 'Унгария', 'en' => 'Hungary', 'value' => 10.37],
            ['code' => 'EL', 'bg' => 'Гърция', 'en' => 'Greece', 'value' => 8.93],
            ['code' => 'BG', 'bg' => 'България', 'en' => 'Bulgaria', 'value' => 8.55],
            ['code' => 'PL', 'bg' => 'Полша', 'en' => 'Poland', 'value' => 8.36],
            ['code' => 'RO', 'bg' => 'Румъния', 'en' => 'Romania', 'value' => 5.21],
        ],
    ],

    /*
     | Why enterprises that CONSIDERED AI decided against it, 2025.
     |
     | Dataset:    isoc_eb_ai, share of enterprises that have ever considered
     |             using AI (NOT of all enterprises — the denominator matters).
     | `considered` is that base: only 11.1% of EU and 4.89% of BG enterprises
     |             ever considered AI at all.
     |
     | The point of the beat: "not useful" is the SMALLEST reason on both sides.
     | The barrier is capacity — expertise, legal clarity, data protection — not
     | scepticism about the technology.
     */
    'ai_barriers' => [
        'considered' => ['bg' => 4.89, 'eu' => 11.10],
        'reasons' => [
            ['bg_label' => 'Липса на експертиза', 'en_label' => 'Lack of expertise', 'bg' => 72.69, 'eu' => 70.31],
            ['bg_label' => 'Правна неяснота', 'en_label' => 'Legal uncertainty', 'bg' => 50.29, 'eu' => 53.61],
            ['bg_label' => 'Защита на данните', 'en_label' => 'Data-protection concerns', 'bg' => 47.68, 'eu' => 52.72],
            ['bg_label' => 'ИИ не е полезен', 'en_label' => 'AI not useful', 'bg' => 16.26, 'eu' => 17.79],
        ],
    ],

    /*
     | Individuals' use of generative AI in the three months before the survey,
     | 2025. A DIFFERENT dataset and population from the enterprise figures —
     | shown to contrast personal uptake with employer uptake, not as a like
     | comparison.
     |
     | Dataset:    isoc_ai_iaiu (indicator I_IUAI, generative AI), persons 16-74
     | source:     https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_by_individuals
     */
    'ai_individuals' => [
        'dataset' => 'isoc_ai_iaiu',
        'extracted_on' => '2026-07-22',
        'bg_16_74' => 22.50,
        'eu_16_74' => 32.66,
        'bg_16_24' => 49.98,
        'bg_25_54' => 26.78,
        'bg_55_74' => 8.09,
        // The enterprise figure, repeated for the side-by-side contrast.
        'bg_enterprise' => 8.55,
    ],
];
