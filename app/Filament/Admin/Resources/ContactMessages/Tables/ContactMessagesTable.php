<?php

namespace App\Filament\Admin\Resources\ContactMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Получено')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('name')->label('Име')->searchable(),
                TextColumn::make('email')->label('Имейл')->searchable()->copyable(),
                TextColumn::make('message')->label('Съобщение')->limit(60)->wrap(),
                TextColumn::make('locale')->label('Език')->badge(),
                ToggleColumn::make('is_read')->label('Прочетено'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
