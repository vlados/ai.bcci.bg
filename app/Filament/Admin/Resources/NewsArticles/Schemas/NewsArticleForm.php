<?php

namespace App\Filament\Admin\Resources\NewsArticles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class NewsArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('slug')
                    ->label('URL адрес (slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Използва се в адреса: /news/{slug}'),
                DatePicker::make('published_at')->label('Дата на публикуване')->required()->default(now()),
                FileUpload::make('image')->label('Изображение')->image()->imageEditor()->directory('news')->columnSpanFull(),
                Toggle::make('is_published')->label('Публикувана')->default(true),
            ])->columns(2),

            Tabs::make('content')->tabs([
                Tab::make('Български')->schema([
                    TextInput::make('title.bg')->label('Заглавие')->required(),
                    Textarea::make('excerpt.bg')->label('Резюме')->rows(3),
                    RichEditor::make('body.bg')->label('Текст'),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title.en')->label('Title'),
                    Textarea::make('excerpt.en')->label('Excerpt')->rows(3),
                    RichEditor::make('body.en')->label('Body'),
                ]),
            ])->columnSpanFull(),

            Section::make('SEO')->description('По желание. Ако е празно, използва се заглавието/резюмето.')->schema([
                TextInput::make('meta_title.bg')->label('Meta title (БГ)'),
                TextInput::make('meta_title.en')->label('Meta title (EN)'),
                Textarea::make('meta_description.bg')->label('Meta description (БГ)')->rows(2),
                Textarea::make('meta_description.en')->label('Meta description (EN)')->rows(2),
            ])->columns(2)->collapsed(),
        ]);
    }
}
