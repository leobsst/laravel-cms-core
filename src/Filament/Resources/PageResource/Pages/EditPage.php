<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages;

use Leobsst\LaravelCmsCore\Models\Page;
use Filament\Actions;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;
    protected static ?string $title = 'Modification de la page';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(mb_strtoupper('Voir')),
            Actions\DeleteAction::make()
                ->hidden(fn () => $this->record->is_default),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['slug'])) {
            $data['slug'] = Page::cleanSlug($data['slug']);
        }
        if (isset($data['content'])) {
            $data['content'] = Page::cleanContent($data['content']);
        }
        return $data;
    }
}
