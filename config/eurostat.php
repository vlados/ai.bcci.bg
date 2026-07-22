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
     | Enterprises with at least a basic level of digital intensity (DII ≥ 4 of
     | 12 criteria) — the Digital Decade headline indicator.
     |
     | Dataset:    isoc_e_dii (indicator E_DI4_GELO, unit PC_ENT, GE10)
     | Note:       biennial; only 2022 and 2024 exist on this methodology, so
     |             it cannot be plotted against the AI years and is quoted in
     |             prose instead. Earlier E_DI/E_DI3 series use a different
     |             definition and are NOT comparable.
     */
    'digital_intensity' => [
        'dataset' => 'isoc_e_dii',
        'indicator' => 'E_DI4_GELO',
        'source_url' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_e_dii/default/table',
        'series' => [
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
];
