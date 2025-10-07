<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Pages\ListFailedJobs;
use Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Schemas\FailedJobsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Tables\FailedJobsTable;
use Leobsst\LaravelCmsCore\Models\FailedJob;

class FailedJobResource extends Resource
{
    protected static ?string $model = FailedJob::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Jobs';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?int $navigationSort = 82;

    public static function form(Schema $schema): Schema
    {
        return FailedJobsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FailedJobsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFailedJobs::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(roles: 'admin');
    }
}
