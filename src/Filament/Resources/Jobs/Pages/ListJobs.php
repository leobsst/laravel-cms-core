<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Jobs\Pages;

use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\Jobs\JobResource;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;
}
