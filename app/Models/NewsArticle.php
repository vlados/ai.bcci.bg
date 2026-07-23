<?php

namespace App\Models;

use App\Concerns\HasTranslations;
use App\Support\FeedBuilder;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'image', 'image_url',
        'meta_title', 'meta_description', 'published_at', 'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'excerpt' => 'array',
        'body' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    /** Publishing or editing an article invalidates the cached RSS feed. */
    protected static function booted(): void
    {
        static::saved(fn () => FeedBuilder::flush());
        static::deleted(fn () => FeedBuilder::flush());
    }

    public function scopePublished($query)
    {
        // `published_at` in the future means scheduled, not published — without
        // the date check a future-dated article leaks onto the site and into
        // the sitemap the moment it is saved.
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Resolved cover image: an uploaded file, else an external URL, else null. */
    public function imageUrl(): ?string
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        if (! $this->image_url) {
            return null;
        }

        // A site-relative path — how the committed covers under public/assets
        // are stored — is resolved against the app URL, so the same seeded
        // value works on any domain. An absolute URL is passed through.
        return str_starts_with($this->image_url, '/')
            ? asset($this->image_url)
            : $this->image_url;
    }
}
