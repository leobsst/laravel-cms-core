<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas;

use Filament\Schemas\Schema;
use Leobsst\LaravelCmsCore\Helpers\LogsHelper;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class FailedJobsForm
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
                    ->formatStateUsing(fn ($state): string => LogsHelper::convertToJson($state))
                    ->columnSpanFull()
                    ->viewerHeight(400),
            ]);
    }
}
