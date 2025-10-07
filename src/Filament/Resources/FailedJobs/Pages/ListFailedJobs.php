<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Pages;

use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\FailedJobResource;

class ListFailedJobs extends ListRecords
{
    protected static string $resource = FailedJobResource::class;
}
