<?php

namespace Leobsst\LaravelCmsCore\Services\Features;

use Leobsst\LaravelCmsCore\DataObjects\Features\FileExplorer\FileExplorerItemDTO;
use Leobsst\LaravelCmsCore\Enums\Features\FileExplorer\FileExplorerItemTypeEnum;

class FileExplorerService
{
    /**
     * Create a new FileExplorerService instance.
     *
     * @param  string|null  $disk  The filesystem disk to use (optional).
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
                type: FileExplorerItemTypeEnum::INDEX,
                name: $disk
            )->toArray();
        }, config('core.features.file_explorer.disks'));
    }

    public function getFinder(?string $path = null): array
    {
        $items = [];

        // Ajoute l'élément parent si on n'est pas à la racine du disque
        if (filled($path)) {
            $items[] = FileExplorerItemDTO::make(
                type: FileExplorerItemTypeEnum::PARENT,
                name: '..',
                disk: $this->disk,
                path: dirname($path) === '.' ? null : dirname($path),
            )->toArray();
        }

        // Dossiers (exclure cachés)
        $directories = array_filter(\Storage::disk($this->disk)->directories($path), function (string $directory): bool {
            return basename($directory)[0] !== '.';
        });
        foreach ($directories as $directory) {
            $items[] = FileExplorerItemDTO::fromItem($directory, $this->disk, true)->toArray();
        }

        // Fichiers (exclure cachés)
        $files = array_filter(\Storage::disk($this->disk)->files($path), function (string $file): bool {
            return basename($file)[0] !== '.';
        });
        foreach ($files as $file) {
            $items[] = FileExplorerItemDTO::fromItem($file, $this->disk, false)->toArray();
        }

        // Tri: parent, dossiers, fichiers
        usort($items, function ($a, $b) {
            $order = [
                FileExplorerItemTypeEnum::PARENT->value => 0,
                FileExplorerItemTypeEnum::FOLDER->value => 1,
                FileExplorerItemTypeEnum::FILE->value => 2,
                FileExplorerItemTypeEnum::ACTION->value => 3,
                FileExplorerItemTypeEnum::INDEX->value => 4,
            ];

            $typeComparison = ($order[$a['type']] ?? 99) <=> ($order[$b['type']] ?? 99);
            if ($typeComparison !== 0) {
                return $typeComparison;
            }

            return strnatcasecmp($a['name'], $b['name']);
        });

        return $items;
    }
}
