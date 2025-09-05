<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas;

use Filament\Schemas\Schema;
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
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
                    ->columnSpanFull()
                    ->viewerHeight(400),
            ]);
    }
}
