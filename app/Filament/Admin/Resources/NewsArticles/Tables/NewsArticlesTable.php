<?php

namespace App\Filament\Admin\Resources\NewsArticles\Tables;

use App\Models\NewsArticle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->label('')
                    ->collection(NewsArticle::COVER)->height(40)->width(60),
                TextColumn::make('title.bg')->label('Заглавие')->searchable()->limit(60)->wrap(),
                TextColumn::make('published_at')->label('Дата')->date('d.m.Y')->sortable(),
                IconColumn::make('is_published')->label('Публ.')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
