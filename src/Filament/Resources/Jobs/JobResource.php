<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Jobs;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\Jobs\Pages\ListJobs;
use Leobsst\LaravelCmsCore\Filament\Resources\Jobs\Schemas\JobsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Jobs\Tables\JobsTable;
use Leobsst\LaravelCmsCore\Models\Job;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Jobs';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return JobsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobs::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(roles: 'admin');
    }
}
