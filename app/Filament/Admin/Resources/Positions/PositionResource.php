<?php

namespace App\Filament\Admin\Resources\Positions;

use App\Filament\Admin\Resources\Positions\Pages\CreatePosition;
use App\Filament\Admin\Resources\Positions\Pages\EditPosition;
use App\Filament\Admin\Resources\Positions\Pages\ListPositions;
use App\Filament\Admin\Resources\Positions\Schemas\PositionForm;
use App\Filament\Admin\Resources\Positions\Tables\PositionsTable;
use App\Models\Position;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationLabel(): string
    {
        return 'Становища';
    }

    public static function getModelLabel(): string
    {
        return 'становище';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Становища';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Съдържание';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return PositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
    }
}
