<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Features;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Models\Feature;
use Leobsst\LaravelCmsCore\Services\FilamentService;

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
            ->recordActions(actions: [
                Action::make(name: 'toggle')
                    ->label(label: fn (Feature $record): string => $record->value ? 'Désactiver' : 'Activer')
                    ->icon(icon: fn (Feature $record): string => $record->value ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->requiresConfirmation(condition: fn (Feature $record): bool => $record->value)
                    ->modalIcon(icon: 'heroicon-o-x-circle')
                    ->modalHeading(heading: fn (Feature $record): string => 'Désactiver  '.$record->name.' ?')
                    ->modalSubmitActionLabel(label: 'Désactiver')
                    ->action(action: function (Feature $record): Notification|bool {
                        if ($record->update(attributes: ['value' => ! $record->value])) {
                            return FilamentService::sendNotification(title: $record->value
                                ? 'Fonctionnalité activée'
                                : 'Fonctionnalité désactivée'
                            );
                        }

                        return FilamentService::sendNotification(title: 'Une erreur est survenue', success: false);
                    }),
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
