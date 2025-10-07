<?php

namespace Leobsst\LaravelCmsCore\DataObjects\Features\Pages;

use Leobsst\LaravelCmsCore\Contracts\Features\PageDataInterface;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageOption;

final class PageOptionDTO implements PageDataInterface
{
    public function __construct(
        public string $name,
        public FieldTypeEnum $type,
        public ?string $value = null,
        public ?string $default_value = null,
    ) {}

    public static function fromModel(PageOption $option): self
    {
        return new self(
            name: $option->name,
            type: $option->type,
            value: $option->value,
            default_value: $option->default_value,
        );
    }

    public static function fromJson(string $data): self
    {
        $data = json_decode($data, true);

        return new self(
            name: $data['name'],
            type: FieldTypeEnum::from($data['type']),
            value: $data['value'] ?? null,
            default_value: $data['default_value'] ?? null,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: FieldTypeEnum::from($data['type']),
            value: $data['value'] ?? null,
            default_value: $data['default_value'] ?? null,
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value,
            'value' => $this->value,
            'default_value' => $this->default_value,
        ];
    }
}
