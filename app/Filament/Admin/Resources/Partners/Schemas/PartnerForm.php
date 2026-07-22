<?php

namespace App\Filament\Admin\Resources\Partners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Име на партньора')->required()->maxLength(255),
            TextInput::make('url')->label('Уебсайт (по желание)')->url()->maxLength(255),
            FileUpload::make('logo')->label('Лого')->image()->directory('partners')->columnSpanFull(),
            TextInput::make('sort_order')->label('Подредба')->numeric()->default(0),
        ])->columns(2);
    }
}
