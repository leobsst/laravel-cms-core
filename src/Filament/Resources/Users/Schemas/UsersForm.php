<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Services\RolesService;

class UsersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                TextInput::make(name: 'name')
                    ->label(label: 'Nom')
                    ->required(),
                TextInput::make(name: 'email')
                    ->label(label: 'Adresse e-mail')
                    ->unique(table: 'user_emails', column: 'email', ignorable: fn ($record): mixed => optional(value: $record)->emails()?->where('email', $record->email)?->first() ?? null)
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
                    ->getOptionLabelFromRecordUsing(callback: fn (Model $record): ?string => RolesService::getRoleLongName(role: $record->name))
                    ->preload(),
                Toggle::make(name: 'enabled')
                    ->label(label: 'Activé')
                    ->default(state: true)
                    ->disabled(condition: fn ($record): bool => (filled($record) && $record->hasRole(['admin'])) || $record === auth()->user()),
            ])->columns(columns: 1);
    }
}
