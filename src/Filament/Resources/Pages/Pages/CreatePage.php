<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Pages;

use Filament\Resources\Pages\CreateRecord;
use Leobsst\LaravelCmsCore\Filament\Resources\Pages\PageResource;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Page::cleanSlug($data['slug']);
        if (isset($data['content'])) {
            $data['content'] = Page::cleanContent($data['content']);
            $data['draft'] = $data['content'];
        }

        return $data;
    }
}
