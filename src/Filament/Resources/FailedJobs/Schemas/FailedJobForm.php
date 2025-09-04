<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas;

use Filament\Schemas\Schema;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class FailedJobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                JsonColumn::make('payload')
                    ->hiddenLabel()
                    ->viewerOnly()
                    ->columnSpanFull()
                    ->viewerHeight(400),
                JsonColumn::make('exception')
                    ->hiddenLabel()
                    ->viewerOnly()
                    ->columnSpanFull()
                    ->viewerHeight(400),
            ]);
    }
}
