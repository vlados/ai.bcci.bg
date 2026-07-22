<?php

namespace App\Filament\Admin\Resources\NewsArticles\Pages;

use App\Filament\Admin\Resources\NewsArticles\NewsArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNewsArticle extends EditRecord
{
    protected static string $resource = NewsArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
