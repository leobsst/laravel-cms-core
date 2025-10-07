<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Filament\Resources\Settings\Schemas\SettingsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Settings\Tables\SettingsTable;
use Leobsst\LaravelCmsCore\Models\Setting;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Personnalisation';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $label = 'Paramètres';

    protected static ?int $navigationSort = 999;

    public static function form(Schema $schema): Schema
    {
        return SettingsForm::configure(schema: $schema);
    }

    public static function table(Table $table): Table
    {
        return SettingsTable::configure(table: $table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Valeur' => $record->value,
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('owner');
    }
}
