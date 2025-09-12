<?php

namespace Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\Tables\FileExplorerTable;

class FileExplorer extends Page implements HasTable
{
    use InteractsWithTable;
    protected string $view = 'laravel-cms-core::filament.pages.file-explorer.file-explorer';

    public static function canAccess(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('file_explorer');
    }

    public static function table(Table $table): Table
    {
        return FileExplorerTable::configure($table);
    }

    public static function getNavigationIcon(): string
    {
        return config('core.file_explorer.navigation_icon', 'heroicon-o-folder');
    }

    public static function getNavigationLabel(): string
    {
        return 'Fichiers';
    }

    public function getTitle(): string
    {
        return 'Explorateur de fichiers';
    }

    public static function getNavigationGroup(): ?string
    {
        return config('core.file_explorer.navigation_group', 'Personnalisation');
    }
}