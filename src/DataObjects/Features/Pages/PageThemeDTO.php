<?php

namespace Leobsst\LaravelCmsCore\DataObjects\Features\Pages;

use Leobsst\LaravelCmsCore\Models\Features\Pages\PageTheme;

final class PageThemeDTO
{
    public function __construct(
        public string $name,
        public ?string $banner = null,
    ) {}

    public static function fromModel(PageTheme $theme): self
    {
        return new self(
            name: $theme->name,
            banner: $theme->banner,
        );
    }

    public static function fromJson(string $data): self
    {
        $data = json_decode($data, true);

        return new self(
            name: $data['name'],
            banner: $data['banner'] ?? null,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            banner: $data['banner'] ?? null,
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'banner' => $this->banner,
        ];
    }
}
