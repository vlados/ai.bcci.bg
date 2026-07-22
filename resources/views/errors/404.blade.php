@php
    $loc = in_array(app()->getLocale(), array_keys(config('site.locales')), true)
        ? app()->getLocale()
        : config('site.default_locale', 'bg');

    $t = fn (string $bg, string $en) => $loc === 'bg' ? $bg : $en;

    // The pages worth offering someone who has landed nowhere.
    $suggestions = ['about', 'positions', 'news', 'survey', 'contacts'];

    // An error page must never be indexed, and must not claim a canonical.
    app(\App\Support\Seo::class)
        ->title($loc === 'bg' ? 'Страницата не е намерена' : 'Page not found')
        ->description($loc === 'bg'
            ? 'Търсената страница не съществува.'
            : 'The page you were looking for does not exist.')
        ->noindex();
@endphp

<x-layouts::app>
    <div class="max-w-[820px] mx-auto px-5 sm:px-8 py-16 lg:py-24">
        <div class="text-[13.5px] font-bold tracking-[2.2px] text-brand mb-4">404</div>

        <h1 class="text-[30px] sm:text-4xl lg:text-[42px] font-bold tracking-[-0.5px] mb-[18px] leading-tight">
            {{ $t('Страницата не е намерена', 'Page not found') }}
        </h1>

        <p class="text-lg leading-[1.65] text-body mb-9">
            {{ $t(
                'Възможно е адресът да е сгрешен или страницата да е преместена. Оттук можете да продължите към основните раздели на сайта.',
                'The address may be mistyped, or the page may have moved. You can continue to the main sections of the site from here.'
            ) }}
        </p>

        <nav aria-label="{{ $t('Предложени страници', 'Suggested pages') }}">
            <ul class="grid gap-px bg-line border border-line">
                @foreach ($suggestions as $key)
                    <li class="bg-white">
                        <a href="{{ route($loc.'.'.$key) }}" wire:navigate
                           class="flex items-center justify-between px-6 py-5 hover:bg-paper">
                            <span class="font-semibold">{{ config("site.nav.$key.$loc") }}</span>
                            <span aria-hidden="true" class="text-brand">→</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <p class="mt-9 text-[15px]">
            <a href="{{ route($loc.'.home') }}" wire:navigate class="font-semibold text-brand">
                ← {{ $t('Към началната страница', 'Back to the homepage') }}
            </a>
        </p>
    </div>
</x-layouts::app>
