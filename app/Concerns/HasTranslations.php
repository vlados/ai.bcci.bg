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

    /**
     * Lets `body.bg` be written as if it were a column of its own.
     *
     * Filament's rich content registers an attribute per editor and writes
     * back through that name — `$record->setAttribute('body.bg', $html)`.
     * Eloquent has no dot syntax, so the query grammar reads `body.bg` as
     * table-and-column and the write fails with "no such column: bg". Folding
     * it into the JSON array here is what makes a translated field usable as a
     * rich content attribute at all.
     */
    public function setAttribute($key, $value)
    {
        if ($locale = $this->translatedAttributeLocale($key)) {
            [$attribute] = explode('.', $key, 2);

            $translations = $this->getAttribute($attribute) ?? [];
            $translations[$locale] = $value;

            return parent::setAttribute($attribute, $translations);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        if ($locale = $this->translatedAttributeLocale($key)) {
            [$attribute] = explode('.', $key, 2);

            return parent::getAttribute($attribute)[$locale] ?? null;
        }

        return parent::getAttribute($key);
    }

    /**
     * The locale in `field.locale`, but only when `field` is one of this
     * model's array-cast columns — so ordinary keys, relations and any other
     * dotted string are left entirely alone.
     */
    protected function translatedAttributeLocale(string $key): ?string
    {
        if (! str_contains($key, '.')) {
            return null;
        }

        [$attribute, $locale] = explode('.', $key, 2);

        if (str_contains($locale, '.') || ! array_key_exists($locale, config('site.locales', []))) {
            return null;
        }

        return ($this->getCasts()[$attribute] ?? null) === 'array' ? $locale : null;
    }
}
