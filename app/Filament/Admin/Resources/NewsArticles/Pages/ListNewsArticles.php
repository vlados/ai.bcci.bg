<?php

namespace App\Filament\Admin\Resources\NewsArticles\Pages;

use App\Filament\Admin\Resources\NewsArticles\NewsArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNewsArticles extends ListRecords
{
    protected static string $resource = NewsArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
