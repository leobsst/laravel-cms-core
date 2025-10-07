<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Logs\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leobsst\LaravelCmsCore\Jobs\SendLogs;
use Leobsst\LaravelCmsCore\Services\FilamentService;

class LogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'created_at')
                    ->dateTime(format: 'd/m/Y H:i:s')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label(label: 'Créé le'),
                TextColumn::make(name: 'updated_at')
                    ->dateTime(format: 'd/m/Y H:i:s')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label(label: 'Finalisé le'),
                TextColumn::make(name: 'type')
                    ->badge()
                    ->color(color: fn ($record): mixed => $record->type->color())
                    ->toggleable(),
                TextColumn::make(name: 'message')
                    ->searchable()
                    ->toggleable()
                    ->label(label: 'Message')
                    ->limit(length: 35),
                TextColumn::make(name: 'status')
                    ->badge()
                    ->color(color: fn ($record): mixed => $record->status->color())
                    ->toggleable()
                    ->label(label: 'Statut'),
                TextColumn::make(name: 'ip_address')
                    ->searchable()
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(label: 'IP'),
                TextColumn::make(name: 'reference_table')
                    ->label(label: 'Table')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(filters: [
                Filter::make(name: 'created_at')
                    ->schema(schema: [
                        DatePicker::make(name: 'created_from')
                            ->label(label: 'Créé entre le'),
                        DatePicker::make(name: 'created_until')
                            ->label(label: 'Et le'),
                    ])
                    ->query(callback: function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                value: $data['created_from'],
                                callback: fn (Builder $query, $date): Builder => $query->whereDate(column: 'created_at', operator: '>=', value: $date),
                            )
                            ->when(
                                value: $data['created_until'],
                                callback: fn (Builder $query, $date): Builder => $query->whereDate(column: 'created_at', operator: '<=', value: $date),
                            );
                    })
                    ->columns(2),
                SelectFilter::make('type')
                    ->options([
                        'info' => 'Information',
                        'warning' => 'Avertissement',
                        'error' => 'Erreur',
                        'success' => 'Succès',
                        'cron' => 'CRON',
                        'debug' => 'Débogage',
                        'critical' => 'Critique',
                        'alert' => 'Alerte',
                        'emergency' => 'Urgence',
                    ])
                    ->multiple(),
                SelectFilter::make('status')
                    ->options([
                        'success' => 'Succès',
                        'error' => 'Erreur',
                    ]),
            ])
            ->recordActions(actions: [
                ViewAction::make()
                    ->modalHeading(heading: ''),
            ])
            ->headerActions(actions: [
                ActionGroup::make(actions: [
                    Action::make(name: 'Exporter')
                        ->icon(icon: 'heroicon-o-document-text')
                        ->action(action: function (): bool | Notification {
                            SendLogs::dispatch(auth()->user())->withoutDelay();

                            return FilamentService::sendNotification('Les logs ont été envoyés par e-mail');
                        }),
                ])->button(),
            ])
            ->toolbarActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(column: 'created_at', direction: 'desc');
    }
}
