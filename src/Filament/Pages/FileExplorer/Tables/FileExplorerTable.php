<?php

namespace Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Leobsst\LaravelCmsCore\Services\Features\FileExplorerService;

class FileExplorerTable
{
    public static ?string $disk = null;
    public static ?string $path = null;
    public static function configure(Table $table): Table
    {
        return $table
            ->records(fn (?string $search): Collection =>
                self::getRecords(
                    search: $search        
                )
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
            ]);
    }

    private static function getRecords(?string $search = null): Collection
    {
        $instance = new FileExplorerService(self::$disk);
        return collect(blank(self::$disk) ? $instance->getIndex() : $instance->getFinder(self::$path))
            ->when(filled($search), fn (Collection $data): Collection => $data->filter(
                fn (array $record): bool => str_contains(
                    strtolower($record['name']),
                    strtolower($search)),
            ));
    }
}