<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\Pages;

use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\JobBatchResource;

class ListJobBatches extends ListRecords
{
    protected static string $resource = JobBatchResource::class;
}
