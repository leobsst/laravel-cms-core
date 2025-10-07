<?php

namespace Leobsst\LaravelCmsCore\DataObjects\Features\FileExplorer;

use Leobsst\LaravelCmsCore\Enums\Features\FileExplorer\FileExplorerItemTypeEnum;

final class FileExplorerItemDTO
{
    public function __construct(
        private FileExplorerItemTypeEnum $type,
        private string $name,
        private ?string $disk = null,
        private ?string $path = null,
        private ?string $mime = null,
        private ?int $size = null,
    ) {}

    /**
     * Manually create a new FileExplorerItemDTO instance.
     */
    public static function make(
        FileExplorerItemTypeEnum $type,
        string $name,
        ?string $disk = null,
        ?string $path = null,
        ?string $mime = null,
        ?int $size = null,
    ): self {
        return new self($type, $name, $disk, $path, $mime, $size);
    }

    /**
     * Create a new FileExplorerItemDTO instance from a filesystem item.
     */
    public static function fromItem(string $item, string $disk, bool $isDirectory = false): self
    {
        $name = basename($item);

        if ($isDirectory) {
            return new self(
                type: FileExplorerItemTypeEnum::FOLDER,
                name: $name,
                disk: $disk,
                path: $item,
            );
        }

        $mime = \Storage::disk($disk)->mimeType($item) ?? null;
        $size = \Storage::disk($disk)->size($item) ?? null;

        return new self(
            type: FileExplorerItemTypeEnum::FILE,
            name: $name,
            disk: $disk,
            path: $item,
            mime: $mime,
            size: is_numeric($size) ? (int) $size : null,
        );
    }

    public function action(string $action): self
    {
        if ($this->type !== FileExplorerItemTypeEnum::ACTION) {
            throw new \LogicException('Cannot set action on non-action item.');
        }

        return new self(
            type: $this->type,
            name: $this->name,
            disk: $this->disk,
        );
    }

    /**
     * Convert the FileExplorerItemDTO instance to an array.
     *
     * @return array{disk: string|null, mime: string|null, name: string, path: string|null, size: int|null, type: string}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'name' => $this->name,
            'disk' => $this->disk,
            'path' => $this->path,
            'mime' => $this->mime,
            'size' => $this->size,
        ];
    }
}
