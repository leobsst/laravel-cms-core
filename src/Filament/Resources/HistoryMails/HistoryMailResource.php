<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\Schemas\HistoryMailsForm;
use Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\Tables\HistoryMailsTable;
use Leobsst\LaravelCmsCore\Models\HistoryMail;

class HistoryMailResource extends Resource
{
    protected static ?string $model = HistoryMail::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Historique';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $label = 'Historique des mails';

    protected static ?string $navigationLabel = 'Mails';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return HistoryMailsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoryMailsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistoryMails::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(roles: 'manager');
    }
}
