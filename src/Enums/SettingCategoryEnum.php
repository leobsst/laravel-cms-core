<?php

namespace Leobsst\LaravelCmsCore\Enums;

enum SettingCategoryEnum: string
{
    case GENERAL = 'general';
    case CUSTOMIZATION = 'customization';
    case CONTACT = 'contact';
    case SOCIAL = 'social';
    case PAYMENT = 'payment';
    case SECURITY = 'security';

    public function title(): string
    {
        return match ($this) {
            self::GENERAL => 'Divers',
            self::CUSTOMIZATION => 'Personnalisation',
            self::CONTACT => 'Contact',
            self::SOCIAL => 'Réseaux sociaux',
            self::PAYMENT => 'Paiement',
            self::SECURITY => 'Administration',
        };
    }
}
