<?php

namespace Leobsst\LaravelCmsCore\Concerns\Enums;

trait CanBeSelectableEnum
{
    public static function asSelectArray(array $excluded = []): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            if (in_array($case->value, $excluded)) {
                continue;
            }

            $options[$case->value] = $case->title();
        }

        return $options;
    }
}
