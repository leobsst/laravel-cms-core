<?php

namespace Leobsst\LaravelCmsCore\DataObjects\Features\Pages;

use Leobsst\LaravelCmsCore\Contracts\Features\PageDataInterface;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

final class PageDTO implements PageDataInterface
{
    public function __construct(
        public string $title,
        public ?string $titleContent = null,
        public ?string $slug = null,
        public ?string $content = null,
        public ?string $draft = null,
        public ?string $banner = null,
        public bool $isPublished = false,
        public bool $isHome = false,
        public bool $isDefault = false,
        public ?string $publishedAt = null,
        public ?array $options = null,
        public ?PageSeoDTO $seo = null,
        public ?PageThemeDTO $theme = null,
    ) {}

    public static function fromModel(Page $page): self
    {
        return new self(
            title: $page->title,
            titleContent: $page->title_content,
            slug: $page->slug,
            content: $page->content,
            draft: $page->draft,
            banner: $page->banner,
            isPublished: $page->is_published,
            isHome: $page->is_home,
            isDefault: $page->is_default,
            publishedAt: $page->published_at,
            options: $page->options->isNotEmpty() ? $page->options->map(fn ($option): array => PageOptionDTO::fromModel($option)->toArray())->toArray() : null,
            seo: $page->seo ? PageSeoDTO::fromModel($page->seo) : null,
            theme: $page->theme ? PageThemeDTO::fromModel($page->theme) : null,
        );
    }

    public static function fromJson(string $data): self
    {
        $data = json_decode($data, true);

        return new self(
            title: $data['title'],
            titleContent: $data['title_content'] ?? null,
            slug: $data['slug'] ?? null,
            content: $data['content'] ?? null,
            draft: $data['draft'] ?? null,
            banner: $data['banner'] ?? null,
            isPublished: $data['is_published'] ?? false,
            isHome: $data['is_home'] ?? false,
            isDefault: $data['is_default'] ?? false,
            publishedAt: $data['published_at'] ?? null,
            options: isset($data['options']) ? array_map(fn ($option): array => PageOptionDTO::fromJson($option)->toArray(), $data['options']) : null,
            seo: isset($data['seo']) ? PageSeoDTO::fromJson($data['seo']) : null,
            theme: isset($data['theme']) ? PageThemeDTO::fromJson($data['theme']) : null,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            titleContent: $data['title_content'] ?? null,
            slug: $data['slug'] ?? null,
            content: $data['content'] ?? null,
            draft: $data['draft'] ?? null,
            banner: $data['banner'] ?? null,
            isPublished: $data['is_published'] ?? false,
            isHome: $data['is_home'] ?? false,
            isDefault: $data['is_default'] ?? false,
            publishedAt: $data['published_at'] ?? null,
            options: isset($data['options']) ? array_map(fn ($option): array => PageOptionDTO::fromArray($option)->toArray(), $data['options']) : null,
            seo: isset($data['seo']) ? PageSeoDTO::fromArray($data['seo']) : null,
            theme: isset($data['theme']) ? PageThemeDTO::fromArray($data['theme']) : null,
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_content' => $this->titleContent,
            'slug' => $this->slug,
            'content' => $this->content,
            'draft' => $this->draft,
            'banner' => $this->banner,
            'is_published' => $this->isPublished,
            'is_home' => $this->isHome,
            'is_default' => $this->isDefault,
            'published_at' => $this->publishedAt,
            'options' => $this->options,
            'seo' => $this->seo?->toArray(),
            'theme' => $this->theme?->toArray(),
        ];
    }
}
