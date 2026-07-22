<?php

namespace App\Filament\Admin\Resources\Positions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('document_date', 'desc')
            ->columns([
                TextColumn::make('document_date')->label('Дата')->date('d.m.Y')->sortable(),
                TextColumn::make('scope')->label('Обхват')->badge()->formatStateUsing(fn ($state) => $state === 'eu' ? 'ЕС' : 'Национално'),
                TextColumn::make('title.bg')->label('Заглавие')->searchable()->limit(70)->wrap(),
                IconColumn::make('pdf_path')->label('PDF')->boolean(),
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
