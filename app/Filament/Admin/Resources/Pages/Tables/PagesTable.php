<?php

namespace App\Filament\Admin\Resources\Pages\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    protected static array $labels = [
        'global' => 'Общи настройки (хедър, футър, контакти)',
        'home' => 'Начало',
        'about' => 'За нас',
        'education' => 'Образование',
        'positions' => 'Становища',
        'survey' => 'Проучване',
        'partners' => 'Партньори',
        'news' => 'Новини',
        'contacts' => 'Контакти',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Страница')
                    ->formatStateUsing(fn ($state) => static::$labels[$state] ?? $state)
                    ->weight('bold'),
            ])
            ->recordActions([
                EditAction::make()->label('Редактирай'),
            ])
            ->paginated(false);
    }
}
