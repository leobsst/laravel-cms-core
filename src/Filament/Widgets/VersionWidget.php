<?php

namespace Leobsst\LaravelCmsCore\Filament\Widgets;

use Filament\Widgets\Widget;

class VersionWidget extends Widget
{
    protected string $view = 'laravel-cms-core::filament.widgets.version-widget';

    protected static bool $isLazy = false;
}
