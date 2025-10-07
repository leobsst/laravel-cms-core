<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\JobBatches;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\Pages\ListJobBatches;
use Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\Schemas\JobBatchesForm;
use Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\Tables\JobBatchesTable;
use Leobsst\LaravelCmsCore\Models\JobBatch;

class JobBatchResource extends Resource
{
    protected static ?string $model = JobBatch::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Jobs';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 81;

    public static function form(Schema $schema): Schema
    {
        return JobBatchesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobBatchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobBatches::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(roles: 'admin');
    }
}
