<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages;

use Filament\Actions\CreateAction;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(mb_strtoupper('Créer')),
        ];
    }
}
