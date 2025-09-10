<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Leobsst\LaravelCmsCore\Filament\Resources\Pages\PageResource;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected static ?string $title = 'Modification de la page';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(mb_strtoupper('Voir')),
            DeleteAction::make()
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
            $data['draft'] = $data['content'];
        }

        return $data;
    }
}
