<?php

namespace App\Models;

use App\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'scope', 'document_date', 'pdf_path', 'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'document_date' => 'date',
        'is_published' => 'boolean',
    ];

    public const SCOPES = [
        'eu' => ['bg' => 'ЕС', 'en' => 'EU'],
        'national' => ['bg' => 'НАЦИОНАЛНО', 'en' => 'NATIONAL'],
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function badgeLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return self::SCOPES[$this->scope][$locale]
            ?? self::SCOPES[$this->scope]['bg']
            ?? $this->scope;
    }
}
