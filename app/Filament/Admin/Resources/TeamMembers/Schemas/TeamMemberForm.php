<?php

namespace App\Filament\Admin\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->label('Име')->required()->maxLength(255),
                TextInput::make('sort_order')->label('Подредба')->numeric()->default(0),
                FileUpload::make('photo')->label('Снимка')->image()->imageEditor()->directory('team')->columnSpanFull(),
            ])->columns(2),

            Tabs::make('role')->tabs([
                Tab::make('Български')->schema([
                    TextInput::make('role.bg')->label('Роля / позиция'),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('role.en')->label('Role / position'),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
