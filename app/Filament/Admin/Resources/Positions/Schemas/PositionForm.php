<?php

namespace App\Filament\Admin\Resources\Positions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('scope')->label('Обхват')->options([
                    'eu' => 'ЕС / EU',
                    'national' => 'Национално / National',
                ])->default('national')->required(),
                DatePicker::make('document_date')->label('Дата на документа')->required()->default(now()),
                FileUpload::make('pdf_path')->label('PDF документ')
                    ->acceptedFileTypes(['application/pdf'])->directory('positions')->downloadable()->columnSpanFull(),
                Toggle::make('is_published')->label('Публикувано')->default(true),
            ])->columns(2),

            Tabs::make('title')->tabs([
                Tab::make('Български')->schema([
                    TextInput::make('title.bg')->label('Заглавие')->required(),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title.en')->label('Title'),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
