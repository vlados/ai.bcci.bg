<?php

namespace App\Filament\Admin\Resources\NewsArticles;

use App\Filament\Admin\Resources\NewsArticles\Pages\CreateNewsArticle;
use App\Filament\Admin\Resources\NewsArticles\Pages\EditNewsArticle;
use App\Filament\Admin\Resources\NewsArticles\Pages\ListNewsArticles;
use App\Filament\Admin\Resources\NewsArticles\Schemas\NewsArticleForm;
use App\Filament\Admin\Resources\NewsArticles\Tables\NewsArticlesTable;
use App\Models\NewsArticle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsArticleResource extends Resource
{
    protected static ?string $model = NewsArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    public static function getNavigationLabel(): string
    {
        return 'Новини';
    }

    public static function getModelLabel(): string
    {
        return 'новина';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Новини';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Съдържание';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return NewsArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsArticlesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsArticles::route('/'),
            'create' => CreateNewsArticle::route('/create'),
            'edit' => EditNewsArticle::route('/{record}/edit'),
        ];
    }
}
