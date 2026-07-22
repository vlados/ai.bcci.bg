<?php

namespace App\Filament\Admin\Resources\Partners;

use App\Filament\Admin\Resources\Partners\Pages\CreatePartner;
use App\Filament\Admin\Resources\Partners\Pages\EditPartner;
use App\Filament\Admin\Resources\Partners\Pages\ListPartners;
use App\Filament\Admin\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Admin\Resources\Partners\Tables\PartnersTable;
use App\Models\Partner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function getNavigationLabel(): string
    {
        return 'Партньори';
    }

    public static function getModelLabel(): string
    {
        return 'партньор';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Партньори';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Съдържание';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return PartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
}
