<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Content editor for the fixed set of site pages. The field list for each page
 * is data-driven from config('site.page_fields'); every page's section is built
 * up front and shown only for its matching record.
 */
class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $sections = [];

        foreach (config('site.page_fields') as $pageKey => $fields) {
            $components = [];
            foreach ($fields as $key => $def) {
                $components[] = static::field($pageKey, $key, $def);
            }

            // SEO override fields for content pages (not the global block).
            if ($pageKey !== 'global') {
                $components[] = Section::make('SEO')
                    ->description('По желание. Ако е празно, се използва заглавието/въведението на страницата.')
                    ->schema([
                        TextInput::make("content.meta_title.bg")->label('Meta title (БГ)'),
                        TextInput::make("content.meta_title.en")->label('Meta title (EN)'),
                        Textarea::make("content.meta_description.bg")->label('Meta description (БГ)')->rows(2),
                        Textarea::make("content.meta_description.en")->label('Meta description (EN)')->rows(2),
                    ])->columns(2)->collapsed();
            }

            $sections[] = Section::make(static::pageTitle($pageKey))
                ->schema($components)
                ->visible(fn (?Page $record) => ($record?->key ?? null) === $pageKey);
        }

        return $schema->components($sections);
    }

    protected static function field(string $pageKey, string $key, array $def)
    {
        $type = $def['type'];
        $label = $def['label'] ?? $key;

        if ($type === 'list') {
            return Repeater::make("content.$key")
                ->label($label)
                ->schema(static::rowComponents($def['fields'] ?? []))
                ->columns(2)
                ->collapsed()
                ->reorderable()
                ->cloneable()
                ->defaultItems(0);
        }

        if (! empty($def['translatable'])) {
            return Grid::make(2)->schema([
                static::input($type, "content.$key.bg", $label.' (БГ)'),
                static::input($type, "content.$key.en", $label.' (EN)'),
            ]);
        }

        return static::input($type, "content.$key", $label)->columnSpanFull();
    }

    protected static function rowComponents(array $fields): array
    {
        $components = [];
        foreach ($fields as $key => $def) {
            $label = $def['label'] ?? $key;
            if (! empty($def['translatable'])) {
                $components[] = static::input($def['type'], "$key.bg", $label.' (БГ)');
                $components[] = static::input($def['type'], "$key.en", $label.' (EN)');
            } else {
                $components[] = static::input($def['type'], $key, $label)->columnSpan(2);
            }
        }

        return $components;
    }

    protected static function input(string $type, string $path, string $label)
    {
        return match ($type) {
            'textarea' => Textarea::make($path)->label($label)->rows(3),
            'richtext' => RichEditor::make($path)->label($label),
            'url' => TextInput::make($path)->label($label)->url(),
            default => TextInput::make($path)->label($label),
        };
    }

    protected static function pageTitle(string $key): string
    {
        if ($key === 'global') {
            return 'Общи настройки (хедър, футър, контакти)';
        }

        return config("site.nav.$key.bg") ?? ucfirst($key);
    }
}
