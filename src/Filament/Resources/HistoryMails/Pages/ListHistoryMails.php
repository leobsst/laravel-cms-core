<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\Pages;

use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\HistoryMailResource;

class ListHistoryMails extends ListRecords
{
    protected static string $resource = HistoryMailResource::class;
}
