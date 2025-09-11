<?php

namespace Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class FileExplorerTable
{
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
            ]);
    }

    private static function getRecords(?string $search = null): Collection
    {
        return collect([
            1 => ['name' => 'Fichier 1', 'size' => 45145, 'mime' => 'image/png', 'path' => 'uploads/pages/content/file1.png'],
            2 => ['name' => 'Fichier 2', 'size' => 12545, 'mime' => 'image/jpg', 'path' => 'uploads/pages/content/file2.jpg'],
            3 => ['name' => 'Fichier 3', 'size' => 7845, 'mime' => 'application/pdf', 'path' => 'uploads/pages/content/file3.pdf'],
        ])
            ->when(filled($search), fn (Collection $data): Collection => $data->filter(
                fn (array $record): bool => str_contains(
                    strtolower($record['name']),
                    strtolower($search)),
            ));
    }
}