<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\MenuResource\Pages;


use Leobsst\LaravelCmsCore\Filament\Resources\MenuResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Modification du '.lcfirst($this->record->name);
    }
}
