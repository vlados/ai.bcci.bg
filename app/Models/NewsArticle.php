<?php

namespace App\Models;

use App\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'image',
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

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
