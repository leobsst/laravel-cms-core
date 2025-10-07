<?php

namespace Leobsst\LaravelCmsCore\Concerns\Features\FileExplorer;

trait CanNavigateThroughFileExplorer
{
    public ?string $disk = null;

    public ?string $path = null;

    public function navigateToDisk(string $disk): void
    {
        $this->disk = $disk;
        $this->path = null;
    }

    public function navigateToParentDirectory(): void
    {
        if (filled($this->path)) {
            $this->path = dirname($this->path) === '.' ? null : dirname($this->path);
        }
    }

    public function navigateToDirectory(string $directory): void
    {
        $this->path = filled($this->path) ? $this->path . '/' . $directory : $directory;
    }

    public function resetNavigation(): void
    {
        $this->disk = null;
        $this->path = null;
    }

    public function getCurrentPath(): string
    {
        return filled($this->disk) ? (filled($this->path) ? $this->disk . ' / ' . $this->path : $this->disk) : 'Racine';
    }

    public function isAtRoot(): bool
    {
        return blank($this->disk);
    }

    public function isAtDiskRoot(): bool
    {
        return filled($this->disk) && blank($this->path);
    }

    public function getCurrentDirectoryName(): string
    {
        return filled($this->path) ? basename($this->path) : (filled($this->disk) ? $this->disk : 'Racine');
    }

    public function getCurrentDirectoryPath(): ?string
    {
        return $this->path;
    }

    public function getCurrentDisk(): ?string
    {
        return $this->disk;
    }

    public function getCurrentNavigationState(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
        ];
    }

    public function setCurrentNavigationState(?string $disk, ?string $path): void
    {
        $this->disk = $disk;
        $this->path = $path;
    }

    public function isNavigating(): bool
    {
        return filled($this->disk) || filled($this->path);
    }
}
