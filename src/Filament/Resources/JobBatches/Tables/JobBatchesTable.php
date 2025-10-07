<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\JobBatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_jobs')
                    ->label('Total Jobs')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pending_jobs')
                    ->label('Jobs en attente')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('failed_jobs')
                    ->label('Jobs échoués')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('failed_job_ids')
                    ->label('IDs des jobs échoués')
                    ->searchable()
                    ->badge(),
                TextColumn::make('cancelled_at')
                    ->label('Annulé le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('finished_at')
                    ->label('Terminé le')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make('view_options')
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
