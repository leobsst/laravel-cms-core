<?php

namespace Leobsst\LaravelCmsCore\Enums;

enum SettingTypeEnum: string
{
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
            default => $this,
        };
    }
}
