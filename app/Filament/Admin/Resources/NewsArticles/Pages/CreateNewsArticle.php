<?php

namespace App\Filament\Admin\Resources\NewsArticles\Pages;

use App\Filament\Admin\Resources\NewsArticles\NewsArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsArticle extends CreateRecord
{
    protected static string $resource = NewsArticleResource::class;
}
