<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users\Pages;

use Filament\Resources\Pages\CreateRecord;
use Leobsst\LaravelCmsCore\Filament\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
