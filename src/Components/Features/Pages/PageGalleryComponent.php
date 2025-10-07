<?php

namespace Leobsst\LaravelCmsCore\Components\Features\Pages;

use Illuminate\View\Component;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageGallery;

class PageGalleryComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private PageGallery $gallery) {}

    /**
     * Basic render method.
     */
    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Contracts\View\Factory
    {
        return view(config('cms-core.features.pages.components.gallery', 'laravel-cms-core::components.features.pages.gallery.page-gallery-component'), [
            'gallery' => $this->gallery,
            'media' => $this->gallery->getMedia('images')->sortByDesc('order_column'),
        ]);
    }
}
