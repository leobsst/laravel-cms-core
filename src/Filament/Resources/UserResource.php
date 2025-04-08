<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources;

use Leobsst\LaravelCmsCore\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Services\RolesService;
use Filament\Resources\Resource;
use Leobsst\LaravelCmsCore\Services\FilamentService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Leobsst\LaravelCmsCore\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Personnalisation';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'Utilisateurs';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                TextInput::make(name: 'name')
                    ->label(label: 'Nom')
                    ->required(),
                TextInput::make(name: 'email')
                    ->label(label: 'Adresse e-mail')
                    ->unique(table: 'user_emails', column: 'email', ignorable: fn($record): mixed => optional(value: $record)->emails()?->where('email', $record->email)?->first() ?? null)
                    ->required(),
                Repeater::make(name: 'emails')
                    ->label(label: 'Adresses e-mail supplémentaires')
                    ->defaultItems(count: 0)
                    ->relationship(
                        name: 'emails',
                        modifyQueryUsing: fn (Builder $query, $record): Builder => $query->where(column: 'email', operator: '!=', value: $record?->email)
                    )
                    ->schema(components: [
                        TextInput::make(name: 'email')
                            ->label(label: 'Adresse e-mail')
                            ->unique(table: 'user_emails', column: 'email', ignorable: fn ($record): mixed => $record ?? null)
                            ->required(),
                    ]),
                Select::make(name: 'roles')
                    ->label(label: 'Rôles')
                    ->default(state: 'user')
                    ->required()
                    ->placeholder(placeholder: 'Sélectionnez au moins un rôle')
                    ->multiple()
                    ->visible(condition: auth()->user()->hasRole(roles: ['admin', 'owner']))
                    ->disabled(condition: fn ($record): bool => filled(value: $record) && $record->hasRole(['admin']))
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => auth()->user()->hasRole(roles: 'admin') ? $query : $query->whereNotIn(column: 'name', values: ['admin', 'owner'])
                    )
                    ->getOptionLabelFromRecordUsing(callback: fn (Model $record): string|null => RolesService::getRoleLongName(role: $record->name))
                    ->preload(),
                Toggle::make(name: 'enabled')
                    ->label(label: 'Activé')
                    ->default(state: true)
                    ->disabled(condition: fn ($record): bool => (filled($record) && $record->hasRole(['admin'])) || $record === auth()->user()),
            ])->columns(columns: 1);
    }

    public static function table(Table $table): Table
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
                    ->formatStateUsing(callback: fn ($record): string|null => RolesService::getRoleLongName(role: $record->role))
                    ->badge()
                    ->color(color: fn ($record): string => match ($record->role) {
                        'user' => 'gray',
                        'editor' => 'info',
                        'manager' => 'success',
                        'owner' => 'yellow',
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
            ->actions(actions: [
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
                ])->button()->color(color: 'gray')
            ])
            ->bulkActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make()
                        ->action(action: function ($records): bool|Notification {
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
                                        $title = 'Impossible de supprimer '.$record->name;
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
            ->checkIfRecordIsSelectableUsing(callback: fn ($record): bool => !$record->hasRole('admin'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'email' => $record->email,
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(roles: 'admin');
    }
}
