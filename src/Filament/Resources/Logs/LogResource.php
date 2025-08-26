<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Logs;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Models\User;
use Leobsst\LaravelCmsCore\Services\FilamentService;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Historique';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $title = 'Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                DateTimePicker::make(name: 'created_at')
                    ->format(format: 'DD/MM/YYYY HH:mm:ss')
                    ->label(label: 'Créé le'),
                DateTimePicker::make(name: 'updated_at')
                    ->format(format: 'DD/MM/YYYY HH:mm:ss')
                    ->label(label: 'Finalisé le'),
                Select::make(name: 'creator_id')
                    ->options(options: User::selectRaw('CONCAT(name, " (", email, ")") AS name, id')->pluck('name', 'id'))
                    ->label(label: 'Créateur'),
                Select::make(name: 'type')
                    ->options(options: [
                        'info' => 'Information',
                        'warning' => 'Avertissement',
                        'error' => 'Erreur',
                        'success' => 'Succès',
                        'debug' => 'Débogage',
                        'critical' => 'Critique',
                        'alert' => 'Alerte',
                        'emergency' => 'Urgence',
                        'cron' => 'CRON',
                    ])
                    ->label(label: 'Type'),
                Select::make(name: 'status')
                    ->options(options: [
                        'success' => 'Succès',
                        'error' => 'Erreur',
                        'running' => 'En cours',
                        'pending' => 'En attente',
                    ])
                    ->label(label: 'Statut'),
                TextInput::make(name: 'ip_address')
                    ->label(label: 'IP'),
                TextInput::make(name: 'reference_table')
                    ->label(label: 'Table'),
                Textarea::make(name: 'message')
                    ->label(label: 'Message')
                    ->columnSpanFull(),
                JsonColumn::make(name: 'data')
                    ->label(label: 'Informations supplémentaires')
                    ->hidden(condition: fn ($state): bool => blank(value: $state))
                    ->viewerOnly()
                    ->columnSpanFull(),
            ])->disabled();
    }

    public static function table(Table $table): Table
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
                        ->action(action: function (): bool|Notification {
                            Log::sendLogsToEmail();

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route(path: '/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(roles: 'admin');
    }
}
