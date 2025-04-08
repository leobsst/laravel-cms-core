<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Leobsst\LaravelCmsCore\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(mb_strtoupper('Supprimer')),
        ];
    }
}
