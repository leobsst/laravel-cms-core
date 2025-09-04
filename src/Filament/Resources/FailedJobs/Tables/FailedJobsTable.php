<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\FailedJobs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FailedJobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue')
                    ->label('File d\'attente')
                    ->sortable()
                    ->badge(),
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payload')
                    ->label('Payload')
                    ->limit(50)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('connection')
                    ->label('Connexion')
                    ->sortable(),
                TextColumn::make('exception')
                    ->label('Exception')
                    ->limit(50)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('failed_at')
                    ->label('Échoué le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make('view_exception')
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
