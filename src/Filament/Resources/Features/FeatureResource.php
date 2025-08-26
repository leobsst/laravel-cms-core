<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Features;

use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Models\Feature;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Personnalisation';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?int $navigationSort = 98;

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'name')
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->label(label: 'Nom')
                    ->searchable()
                    ->sortable(),
                IconColumn::make(name: 'value')
                    ->label(label: 'Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort(column: 'name')
            ->filters(filters: [
                TernaryFilter::make(name: 'value')
                    ->label(label: 'Active')
                    ->trueLabel(trueLabel: 'Oui')
                    ->falseLabel(falseLabel: 'Non')
                    ->placeholder(placeholder: 'Toutes'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
