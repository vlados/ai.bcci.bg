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

        // Ceiling for bar heights. EU 2025 (19.95) sits just under it, so the
        // bars read as a true proportion rather than a rescaled one.
        'scale_max' => 20,
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
