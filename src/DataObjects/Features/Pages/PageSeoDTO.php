<?php

namespace Leobsst\LaravelCmsCore\DataObjects\Features\Pages;

use Leobsst\LaravelCmsCore\Contracts\Features\PageDataInterface;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PagesSeo;

final class PageSeoDTO implements PageDataInterface
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $robots = 'index, follow',
        public ?string $ogImage = null,
        public ?string $ogType = 'website',
        public ?string $ogLocale = 'fr_FR',
        public ?string $twitterCard = 'summary_large_image',
        public ?string $twitterImage = null,
    ) {}

    public static function fromModel(PagesSeo $seo): self
    {
        return new self(
            title: $seo->title,
            description: $seo->description,
            robots: $seo->robots,
            ogImage: $seo->og_image,
            ogType: $seo->og_type,
            ogLocale: $seo->og_locale,
            twitterCard: $seo->twitter_card,
            twitterImage: $seo->twitter_image,
        );
    }

    public static function fromJson(string $data): self
    {
        $data = json_decode($data, true);

        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            robots: $data['robots'] ?? 'index, follow',
            ogImage: $data['og_image'] ?? null,
            ogType: $data['og_type'] ?? 'website',
            ogLocale: $data['og_locale'] ?? 'fr_FR',
            twitterCard: $data['twitter_card'] ?? 'summary_large_image',
            twitterImage: $data['twitter_image'] ?? null,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            robots: $data['robots'] ?? 'index, follow',
            ogImage: $data['og_image'] ?? null,
            ogType: $data['og_type'] ?? 'website',
            ogLocale: $data['og_locale'] ?? 'fr_FR',
            twitterCard: $data['twitter_card'] ?? 'summary_large_image',
            twitterImage: $data['twitter_image'] ?? null,
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'robots' => $this->robots,
            'og_image' => $this->ogImage,
            'og_type' => $this->ogType,
            'og_locale' => $this->ogLocale,
            'twitter_card' => $this->twitterCard,
            'twitter_image' => $this->twitterImage,
        ];
    }
}
