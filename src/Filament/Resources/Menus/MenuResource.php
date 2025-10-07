<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Menus;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\Menus\Schemas\MenusForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Menus\Tables\MenusTable;
use Leobsst\LaravelCmsCore\Models\Features\Menus\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Personnalisation';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $title = 'Menus';

    protected static ?int $navigationSort = 97;

    public static function form(Schema $schema): Schema
    {
        return MenusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'edit' => Pages\EditMenu::route('/{record}'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('manager') && (\Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('menus'));
    }
}
