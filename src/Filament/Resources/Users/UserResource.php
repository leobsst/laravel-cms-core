<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Filament\Resources\Users\Schemas\UsersForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Users\Tables\UsersTable;
use Leobsst\LaravelCmsCore\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Personnalisation';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Utilisateurs';

    protected static ?int $navigationSort = 98;

    public static function form(Schema $schema): Schema
    {
        return UsersForm::configure(schema: $schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure(table: $table);
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

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(roles: 'manager');
    }
}
