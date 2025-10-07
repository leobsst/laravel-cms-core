<?php

namespace Leobsst\LaravelCmsCore\Concerns\Features\Pages;

use Leobsst\LaravelCmsCore\Components\Features\Pages\PageGalleryComponent;

trait HasGalleryComponent
{
    /**
     * Replace gallery shortcodes in content with the actual gallery component render
     *
     * @param  string|null  $content  The content containing gallery shortcodes
     * @param  \Illuminate\Database\Eloquent\Collection|null  $galleries  The collection of galleries to replace
     * @return string|null The content with gallery components rendered
     */
    public function getGalleryComponent(?string $content = null, ?\Illuminate\Database\Eloquent\Collection $galleries = null): ?string
    {
        if (str_contains($content, '[[gallery:')) {
            foreach ($galleries ?? [] as $gallery) {
                $content = str_replace(
                    '[[gallery:' . $gallery->identifier . ']]',
                    (new PageGalleryComponent($gallery))->render(),
                    $content
                );
            }
        }

        return $content;
    }
}
