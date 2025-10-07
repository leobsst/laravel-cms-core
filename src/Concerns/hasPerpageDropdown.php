<?php

namespace Leobsst\LaravelCmsCore\Concerns;

/**
 * @phpstan-ignore-next-line trait.unused (trait provided for external use)
 */
trait hasPerpageDropdown
{
    public $per_page = 10;

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
