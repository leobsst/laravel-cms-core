<?php

namespace Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Leobsst\LaravelCmsCore\Concerns\Features\FileExplorer\CanNavigateThroughFileExplorer;
use Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\Tables\FileExplorerTable;
use Leobsst\LaravelCmsCore\Services\Features\FileExplorerService;

class FileExplorer extends Page implements HasTable
{
    use CanNavigateThroughFileExplorer;
    use InteractsWithTable;

    protected string $view = 'laravel-cms-core::filament.pages.file-explorer.file-explorer';

    protected static ?int $navigationSort = 99;

    public string $viewMode = 'list'; // 'list' | 'grid'

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('editor') && (\Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('file_explorer'));
    }

    public static function table(Table $table): Table
    {
        return FileExplorerTable::configure($table);
    }

    public static function getNavigationIcon(): string
    {
        return config('cms-core.file_explorer.navigation_icon', 'heroicon-o-folder');
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
        return config('cms-core.file_explorer.navigation_group', 'Personnalisation');
    }

    public function setListView(): void
    {
        $this->viewMode = 'list';
    }

    public function setGridView(): void
    {
        $this->viewMode = 'grid';
    }

    public function getGridRecords(): Collection
    {
        $service = new FileExplorerService($this->getCurrentDisk());

        $items = blank($this->getCurrentDisk())
            ? $service->getIndex()
            : $service->getFinder($this->getCurrentDirectoryPath());

        return collect($items);
    }
}
