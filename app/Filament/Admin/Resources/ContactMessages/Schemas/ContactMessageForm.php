<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Име'),
            TextInput::make('email')->label('Имейл'),
            TextInput::make('locale')->label('Език'),
            Textarea::make('message')->label('Съобщение')->rows(6)->columnSpanFull(),
        ])->columns(2);
    }
}
