<?php

namespace App\Filament\Admin\Resources\Pages\Pages;

use App\Filament\Admin\Resources\Pages\PageResource;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function getTitle(): string
    {
        $labels = [
            'global' => 'Общи настройки', 'home' => 'Начало', 'about' => 'За нас',
            'education' => 'Образование', 'positions' => 'Становища', 'survey' => 'Проучване',
            'partners' => 'Партньори', 'news' => 'Новини', 'contacts' => 'Контакти',
        ];

        return $labels[$this->record->key] ?? 'Редакция';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
