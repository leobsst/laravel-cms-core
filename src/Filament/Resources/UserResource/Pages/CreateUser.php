<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\UserResource\Pages;

use Leobsst\LaravelCmsCore\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
