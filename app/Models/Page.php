<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['key', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    /** @var array<string, self> */
    protected static array $memo = [];

    /**
     * Fetch (and memoize per request) a page record by key. Returns a blank
     * model when the key isn't seeded yet, so views never hard-fail.
     */
    public static function forKey(string $key): self
    {
        return static::$memo[$key] ??= static::firstOrNew(['key' => $key]);
    }

    /**
     * Read a content field, resolving the active locale for translatable
     * values. Non-translatable fields are stored as a plain scalar.
     */
    public function get(string $field, ?string $locale = null): mixed
    {
        $value = data_get($this->content, $field);
        $locale ??= app()->getLocale();

        if (is_array($value) && (isset($value['bg']) || isset($value['en']))) {
            return $value[$locale]
                ?? $value[config('site.default_locale', 'bg')]
                ?? '';
        }

        return $value;
    }

    /**
     * Read a repeater/list field, resolving locale on each row's fields.
     * Returns an array of associative rows with scalar (localised) values.
     */
    public function list(string $field, ?string $locale = null): array
    {
        $rows = data_get($this->content, $field);
        if (! is_array($rows)) {
            return [];
        }

        $locale ??= app()->getLocale();
        $default = config('site.default_locale', 'bg');

        return array_map(function ($row) use ($locale, $default) {
            if (! is_array($row)) {
                return $row;
            }

            return array_map(function ($cell) use ($locale, $default) {
                if (is_array($cell) && (isset($cell['bg']) || isset($cell['en']))) {
                    return $cell[$locale] ?? $cell[$default] ?? '';
                }

                return $cell;
            }, $row);
        }, array_values($rows));
    }
}
