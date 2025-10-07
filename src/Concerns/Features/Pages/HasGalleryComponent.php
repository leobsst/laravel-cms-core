<?php

namespace Leobsst\LaravelCmsCore\Concerns\Features\Pages;

use Leobsst\LaravelCmsCore\Components\Features\Pages\PageGalleryComponent;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageGallery;

trait HasGalleryComponent
{
    /**
     * Replace gallery shortcodes in content with the actual gallery component render
     *
     * @param  string|null  $content  The content containing gallery shortcodes
     * @param  \Illuminate\Database\Eloquent\Collection<int, PageGallery>|null  $galleries  The collection of galleries to replace
     * @return string|null The content with gallery components rendered
     */
    public function getGalleryComponent(?string $content = null, ?\Illuminate\Database\Eloquent\Collection $galleries = null): ?string
    {
        if ($content && str_contains($content, '[[gallery:')) {
            foreach ($galleries ?? [] as $gallery) {
                /** @var PageGallery $gallery */
                $component = new PageGalleryComponent($gallery);
                $rendered = $component->render();

                $content = str_replace(
                    '[[gallery:' . $gallery->identifier . ']]',
                    $rendered instanceof \Illuminate\Contracts\View\View ? $rendered->render() : (string) $rendered,
                    $content
                );
            }
        }

        return $content;
    }
}
