<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Menus\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with('children'));
    }
}
