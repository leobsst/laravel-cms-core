<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;
use Leobsst\LaravelCmsCore\Models\Setting;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('setting_name')
                    ->searchable()
                    ->label('Nom')
                    ->tooltip(fn (Setting $record): ?string => $record->description),
                TextColumn::make('custom')
                    ->searchable(['value'])
                    ->label('Valeur')
                    ->toggleable()
                    ->default(fn ($record): mixed => match ($record->type) {
                        FieldTypeEnum::TAGS => $record->tags->pluck('name')->toArray(),
                        FieldTypeEnum::COLOR => new HtmlString('
                            <span style="border-radius: 100%; width: 1.5rem; height: 1.5rem; display: block; background-color: ' . $record->value . ';">&nbsp;</span>
                        '),
                        FieldTypeEnum::IMAGE => filled($record->value) ? new HtmlString('
                            <img src="' . $record->value . '" alt="' . $record->name . '" style="width: 3rem; height: 3rem; border-radius: 0.375rem;">
                        ') : null,
                        FieldTypeEnum::BOOLEAN => (bool) $record->value
                            ? new HtmlString('
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 2rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            ')
                            : new HtmlString('
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 2rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            '),
                        default => $record->value,
                    })
                    ->badge(fn (Setting $record): bool => $record->type === FieldTypeEnum::TAGS)
                    ->wrap(fn (Setting $record): bool => $record->type === FieldTypeEnum::TAGS)
                    ->color(fn (Setting $record): ?string => match ($record->type) {
                        FieldTypeEnum::BOOLEAN => (bool) $record->value ? 'success' : 'danger',
                        default => null
                    })
                    ->limit(fn (Setting $record): ?int => in_array($record->type, [
                        FieldTypeEnum::STRING,
                        FieldTypeEnum::TEXTAREA,
                    ]) ? 80 : null),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (Setting $record) => $record->type->title())
                    ->toggleable(),
                IconColumn::make('protected')
                    ->label('Protégé')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Modification du paramètre')
                    ->modalWidth('xl')
                    ->disabled(condition: fn (Setting $record): bool => $record->protected && ! auth()->user()->hasRole('admin'))
                    ->visible(
                        fn (Setting $record): bool => $record->protected && ! auth()->user()->hasRole('admin')
                        ? false
                        : $record->enabled
                    ),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Setting $record) => ! $record->is_default)
            ->modifyQueryUsing(fn ($query) => $query->orderBy('name'));
    }
}
