<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Leobsst\LaravelCmsCore\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(mb_strtoupper('Supprimer')),
        ];
    }
}
