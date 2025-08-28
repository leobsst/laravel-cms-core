<?php

namespace Leobsst\LaravelCmsCore\Livewire\Page\Partials;

use Livewire\Component;

class Content extends Component
{
    public ?string $content = null;

    /**
     * placeholder view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function placeholder()
    {
        return view('laravel-cms-core::components.pages.content.placeholder');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view(config('core.features.pages.content_view', 'laravel-cms-core::livewire.page.partials.content'));
    }
}
