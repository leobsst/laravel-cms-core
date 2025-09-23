<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
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
                Section::make('raw')
                    ->columns(1)
                    ->collapsible()
                    ->columnSpanFull()
                    ->contained(false)
                    ->schema([
                        Textarea::make('exception')
                            ->disabled()
                            ->rows(10)
                            ->columnSpanFull(),
                    ]),
                Section::make('formatted')
                    ->columns(1)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->contained(false)
                    ->schema([
                        JsonColumn::make('exception')
                            ->hiddenLabel()
                            ->viewerOnly()
                            ->formatStateUsing(fn ($state): string => LogsHelper::convertToJson($state))
                            ->columnSpanFull()
                            ->viewerHeight(400),
                    ]),
            ]);
    }
}
