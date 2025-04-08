<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages;

use Leobsst\LaravelCmsCore\Models\Page;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Page::cleanSlug($data['slug']);
        if (isset($data['content'])) {
            $data['content'] = Page::cleanContent($data['content']);
        }
        return $data;
    }
}
