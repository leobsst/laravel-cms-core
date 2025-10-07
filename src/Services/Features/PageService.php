<?php

namespace Leobsst\LaravelCmsCore\Services\Features;

use Leobsst\LaravelCmsCore\DataObjects\Features\Pages\PageDTO;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

class PageService
{
    public function __construct(private ?Page $page = null) {}

    public function setPage(Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function exportToJson(): string
    {
        return PageDTO::fromModel($this->page->load(['seo', 'theme']))->toJson();
    }
}
