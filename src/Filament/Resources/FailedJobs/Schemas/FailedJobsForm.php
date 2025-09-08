<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas;

use Filament\Forms\Components\Textarea;
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
                Textarea::make('exception')
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->rows(30),
            ]);
    }
}
