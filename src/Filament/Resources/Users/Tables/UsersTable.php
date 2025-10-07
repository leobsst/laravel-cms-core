<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Models\User;
use Leobsst\LaravelCmsCore\Services\FilamentService;
use Leobsst\LaravelCmsCore\Services\RolesService;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->label(label: 'Nom')
                    ->sortable()
                    ->searchable(),
                TextColumn::make(name: 'email')
                    ->label(label: 'Adresse e-mail')
                    ->searchable(),
                TextColumn::make(name: 'role')
                    ->label(label: 'Rôle')
                    ->formatStateUsing(callback: fn ($record): ?string => RolesService::getRoleLongName(role: $record->role))
                    ->badge()
                    ->color(color: fn ($record): string => match ($record->role) {
                        'user' => 'gray',
                        'editor' => 'info',
                        'manager' => 'success',
                        'owner' => 'warning',
                        'admin' => 'danger',
                    })
                    ->toggleable(),
                ToggleColumn::make(name: 'enabled')
                    ->label(label: 'Activé')
                    ->sortable()
                    ->toggleable()
                    ->disabled(condition: fn ($record): bool => $record->hasRole('admin') || $record === auth()->user()),
                TextColumn::make(name: 'created_at')
                    ->label(label: 'Créé le')
                    ->sortable()
                    ->formatStateUsing(callback: function ($record): mixed {
                        return $record->created_at->format('d/m/Y');
                    })
                    ->toggleable(),
            ])
            ->recordActions(actions: [
                ActionGroup::make(actions: [
                    EditAction::make()
                        ->modalHeading(heading: 'Modification de l\'utilisateur')
                        ->modalWidth(width: 'md'),
                    DeleteAction::make()
                        ->hidden(condition: fn ($record): mixed => $record->hasRole('admin'))
                        ->action(action: function ($record): void {
                            if ($record->hasRole('admin')) {
                                FilamentService::sendNotification(
                                    title: 'Impossible de supprimer cet utilisateur',
                                    success: false,
                                    color: 'danger',
                                    body: 'Cet utilisateur est un administrateur et ne peut pas être supprimé.'
                                );
                            } else {
                                $record->delete();
                                FilamentService::sendNotification(title: 'Utilisateur supprimé avec succès');
                            }
                        }),
                ])->button()->color(color: 'gray'),
            ])
            ->toolbarActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make()
                        ->action(action: function ($records): bool | Notification {
                            if (count($records) === User::count()) {
                                return FilamentService::sendNotification(
                                    title: 'Impossible de supprimer tous les utilisateurs',
                                    success: false,
                                    color: 'danger',
                                    body: 'Il doit y avoir au moins un utilisateur.'
                                );
                            } else {
                                foreach ($records as $record) {
                                    if ($record->hasRole('admin')) {
                                        $title = 'Impossible de supprimer ' . $record->name;

                                        return FilamentService::sendNotification(
                                            title: $title,
                                            success: false,
                                            color: 'danger',
                                            body: 'Cet utilisateur est un administrateur et ne peut pas être supprimé.'
                                        );
                                    } else {
                                        $record->delete();
                                    }
                                }

                                return FilamentService::sendNotification(title: 'Utilisateurs supprimés avec succès');
                            }
                        }),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(callback: fn ($record): bool => ! $record->hasRole('admin'));
    }
}
