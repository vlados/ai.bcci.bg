<?php

namespace App\Concerns;

/*
| Lightweight translation helper for models that store a field as a JSON
| array cast keyed by locale, e.g. title = ['bg' => '…', 'en' => '…'].
|
| Filament binds naturally to `title.bg` / `title.en` on an array-cast column,
| so editing needs no extra package. This trait only adds convenient,
| locale-aware reading with a fallback to the default locale.
*/
trait HasTranslations
{
    public function tr(string $field, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $value = $this->getAttribute($field);

        if (is_array($value)) {
            return (string) (
                $value[$locale]
                ?? $value[config('site.default_locale', 'bg')]
                ?? (reset($value) ?: '')
            );
        }

        return (string) ($value ?? '');
    }
}
