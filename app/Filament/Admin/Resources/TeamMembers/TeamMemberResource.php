<?php

namespace App\Filament\Admin\Resources\TeamMembers;

use App\Filament\Admin\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Admin\Resources\TeamMembers\Pages\EditTeamMember;
use App\Filament\Admin\Resources\TeamMembers\Pages\ListTeamMembers;
use App\Filament\Admin\Resources\TeamMembers\Schemas\TeamMemberForm;
use App\Filament\Admin\Resources\TeamMembers\Tables\TeamMembersTable;
use App\Models\TeamMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationLabel(): string
    {
        return 'Екип';
    }

    public static function getModelLabel(): string
    {
        return 'член на екипа';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Екип';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Съдържание';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function form(Schema $schema): Schema
    {
        return TeamMemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamMembersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }
}
