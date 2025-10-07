<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page\Partials;

use Illuminate\Database\Eloquent\Collection;
use Leobsst\LaravelCmsCore\Concerns\Features\Pages\HasGalleryComponent;
use Livewire\Component;

class Content extends Component
{
    use HasGalleryComponent;

    public ?string $content = null;

    public ?Collection $galleries = null;

    public function mount(): void
    {
        $this->content = $this->getGalleryComponent($this->content, $this->galleries);
    }

    /**
     * placeholder view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function placeholder()
    {
        return view('laravel-cms-core::components.features.pages.content.placeholder');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view(config('cms-core.features.pages.content_view', 'laravel-cms-core::livewire.page.partials.content'));
    }
}
