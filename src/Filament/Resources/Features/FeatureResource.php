<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Features;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\Features\Tables\FeaturesTable;
use Leobsst\LaravelCmsCore\Models\Feature;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Personnalisation';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?int $navigationSort = 90;

    public static function table(Table $table): Table
    {
        return FeaturesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
