<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Logs;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\Logs\Schemas\LogsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Logs\Tables\LogsTable;
use Leobsst\LaravelCmsCore\Models\Log;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Historique';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $title = 'Logs';

    protected static ?int $navigationSort = 71;

    public static function form(Schema $schema): Schema
    {
        return LogsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogsTable::configure($table);
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
