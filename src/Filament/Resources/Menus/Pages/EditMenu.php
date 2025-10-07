<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Menus\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Leobsst\LaravelCmsCore\Filament\Resources\Menus\MenuResource;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Modification du ' . lcfirst($this->record->name);
    }
}
