<?php

namespace Leobsst\LaravelCmsCore\Enums\Features\Pages;

use Leobsst\LaravelCmsCore\Concerns\Enums\CanBeSelectableEnum;

enum PageGalleryAlignment: string
{
    use CanBeSelectableEnum;

    case LEFT = 'left';
    case CENTER = 'center';
    case RIGHT = 'right';

    public function title(): string
    {
        return match ($this) {
            self::LEFT => 'Gauche',
            self::CENTER => 'Centre',
            self::RIGHT => 'Droite',
        };
    }
}
