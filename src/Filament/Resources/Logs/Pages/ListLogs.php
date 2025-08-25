<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Logs\Pages;

use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\Logs\LogResource;

class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;
}
