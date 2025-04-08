<?php

namespace Leobsst\LaravelCmsCore\Traits;

/**
 * 
 */
trait hasPerpageDropdown
{
    public $per_page = 10;

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
