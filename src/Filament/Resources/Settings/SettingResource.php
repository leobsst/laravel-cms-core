<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings;

use Filament\Actions\EditAction;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Leobsst\LaravelCmsCore\Filament\Resources\Settings\Schemas\SettingsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Settings\Tables\SettingsTable;
use Leobsst\LaravelCmsCore\Models\Setting;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Personnalisation';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $label = 'Paramètres';

    protected static ?int $navigationSort = 99;

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

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
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
