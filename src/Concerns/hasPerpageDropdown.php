<?php

namespace Leobsst\LaravelCmsCore\Concerns;

trait hasPerpageDropdown
{
    public $per_page = 10;

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
