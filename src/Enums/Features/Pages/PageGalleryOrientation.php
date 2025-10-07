<?php

namespace Leobsst\LaravelCmsCore\Enums\Features\Pages;

use Leobsst\LaravelCmsCore\Concerns\Enums\CanBeSelectableEnum;

enum PageGalleryOrientation: string
{
    use CanBeSelectableEnum;

    case HORIZONTAL = 'horizontal';
    case VERTICAL = 'vertical';

    public function title(): string
    {
        return match ($this) {
            self::HORIZONTAL => 'Horizontal',
            self::VERTICAL => 'Vertical',
        };
    }
}
