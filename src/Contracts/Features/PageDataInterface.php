<?php

namespace Leobsst\LaravelCmsCore\Contracts\Features;

interface PageDataInterface
{
    public static function fromJson(string $json): self;

    public static function fromArray(array $data): self;

    public function toJson(): string;

    public function toArray(): array;
}
