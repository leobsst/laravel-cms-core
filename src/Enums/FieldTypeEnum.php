<?php

namespace Leobsst\LaravelCmsCore\Enums;

use Leobsst\LaravelCmsCore\Concerns\Enums\CanBeSelectableEnum;

enum FieldTypeEnum: string
{
    use CanBeSelectableEnum;

    case STRING = 'string';
    case URL = 'url';
    case EMAIL = 'email';
    case PASSWORD = 'password';
    case COLOR = 'color';
    case IMAGE = 'image';
    case TAGS = 'tags';
    case BOOLEAN = 'boolean';
    case NUMBER = 'number';
    case TEXTAREA = 'textarea';
    case JSON = 'json';
    case SERIALIZED = 'serialized';
    case DATE = 'date';
    case RANGE_INT = 'range_int';
    case RANGE_FLOAT = 'range_float';

    public function title(): string
    {
        return match ($this) {
            self::STRING => 'Chaîne de caractères',
            self::URL => 'URL',
            self::EMAIL => 'Email',
            self::PASSWORD => 'Mot de passe',
            self::COLOR => 'Couleur',
            self::IMAGE => 'Image',
            self::TAGS => 'Tags',
            self::BOOLEAN => 'Booléen',
            self::NUMBER => 'Nombre',
            self::JSON => 'JSON',
            self::SERIALIZED => 'Sérialisé',
            self::DATE => 'Date',
            self::TEXTAREA => 'Zone de texte',
            self::RANGE_INT => 'Plage de nombres entiers',
            self::RANGE_FLOAT => 'Plage de nombres décimaux',
        };
    }
}
