<?php

namespace Leobsst\LaravelCmsCore\Services\Features;

use Leobsst\LaravelCmsCore\DataObjects\Features\FileExplorer\FileExplorerItemDTO;
use Leobsst\LaravelCmsCore\Enums\Features\FileExplorer\FileExplorerItemTypeEnum;

class FileExplorerService
{
    /**
     * Create a new FileExplorerService instance.
     *
     * @param string|null $disk The filesystem disk to use (optional).
     */
    public function __construct(private ?string $disk = null) {}

    /**
     * Get the index of available disks as FileExplorerItemDTO arrays.
     *
     * @return array[]
     */
    public function getIndex(): array
    {
        return array_map(function ($disk) {
            return FileExplorerItemDTO::make(
                type: FileExplorerItemTypeEnum::ACTION,
                name: $disk
            )->toArray();
        }, config('core.features.file_explorer.disks'));
    }

    public function getFinder(?string $path = null): array
    {
        $items = [];

        // Add the parent directory item if a path is provided
        /* if (filled($path)) {
            $items[] = FileExplorerItemDTO::make(
                type: FileExplorerItemTypeEnum::PARENT,
                name: '..',
                disk: $this->disk,
            )->toArray();
        } */

        // Retrieve and map files and directories from the specified disk and path
        /* $storageItems = \Storage::disk($this->disk)->files($path);
        foreach ($storageItems as $item) {
            $items[] = FileExplorerItemDTO::fromItem($item)->toArray();
        } */

        return $items;
    }
}