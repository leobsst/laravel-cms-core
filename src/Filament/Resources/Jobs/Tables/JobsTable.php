<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Jobs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue')
                    ->label('File d\'attente')
                    ->sortable()
                    ->badge(),
                TextColumn::make('payload')
                    ->label('Payload')
                    ->limit(50)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attempts')
                    ->label('Tentatives')
                    ->sortable(),
                TextColumn::make('reserved_at')
                    ->label('Réservé le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('available_at')
                    ->label('Disponible le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make('view_payload')
                    ->modalHeading('')
                    ->modalWidth(Width::ThreeExtraLarge),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
