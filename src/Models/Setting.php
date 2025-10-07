<?php

namespace Leobsst\LaravelCmsCore\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

/**
 * Class Setting
 *
 * @property int $id
 * @property string $name
 * @property ?string $value
 * @property ?string $default_value
 * @property FieldTypeEnum $type
 * @property SettingCategoryEnum $category
 * @property bool $is_default
 * @property bool $enabled
 * @property bool $protected
 * @property string $setting_name
 * @property string|null $description
 * @property Tag[] $tags
 */
class Setting extends Model
{
    use HasTags;

    protected $fillable = [
        'name',
        'value',
        'default_value',
        'type',
        'category',
        'is_default',
        'enabled',
        'protected',
    ];

    protected $casts = [
        'type' => FieldTypeEnum::class,
        'category' => SettingCategoryEnum::class,
        'is_default' => 'boolean',
        'enabled' => 'boolean',
        'protected' => 'boolean',
    ];

    /*
    * Set website name
    */
    public static function set(string $parameter, mixed $value): bool | Exception
    {
        return self::where('name', $parameter)->firstOrFail()->update(['value' => $value]);
    }

    /*
    *  Get website name
    *  @return null|string|Exception
    */
    public static function get(string $parameter): null | string | Exception
    {
        return self::where('name', $parameter)->firstOrFail()->value;
    }

    /**
     * Get website tags
     */
    public function scopeWebsiteTags(): Collection
    {
        return self::firstWhere('name', 'website_keywords')->tags()->pluck('name');
    }

    public function getSettingNameAttribute(): string
    {
        return match ($this->name) {
            'website_name' => 'Nom du site',
            'website_url' => 'URL du site',
            'website_keywords' => 'Mots-clés du site',
            'website_description' => 'Description du site',
            'website_logo' => 'Logo du site',
            'address' => 'Adresse',
            'city' => 'Ville',
            'zip' => 'Code postal',
            'country' => 'Pays',
            'phone_number' => 'Téléphone',
            'email_address' => 'Adresse email',
            'primary_color' => 'Couleur principale',
            'primary_color_dark' => 'Couleur principale foncée',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'youtube' => 'Youtube',
            'twitch' => 'Twitch',
            'github' => 'Github',
            'custom_css' => 'CSS personnalisé',
            'under_maintenance' => 'Site en maintenance ?',
            'alert_box_message' => 'Message de la popup d\'alerte',
            'alert_box_enabled' => 'Popup d\'alerte activée ?',
            'business_entity_enabled' => 'Entité commerciale ?',
            'business_entity_name' => 'Raison sociale',
            'business_entity_siret' => 'SIRET',
            'business_entity_vat_number' => 'Numéro de TVA',
            'business_entity_address' => 'Adresse',
            'business_entity_city' => 'Ville',
            'business_entity_zip' => 'Code postal',
            'business_entity_country' => 'Pays',
            'business_entity_phone_number' => 'Téléphone',
            'business_entity_email_address' => 'Email',
            'business_entity_owner' => 'Représentant légal',
            default => $this->name,
        };
    }

    public function getDescriptionAttribute(): ?string
    {
        return match ($this->name) {
            'website_description' => 'Description du site pour les moteurs de recherche',
            'email_address' => 'Adresse email de contact du site',
            'primary_color' => 'Couleur principale utilisée dans le thème du site',
            'primary_color_dark' => 'Couleur principale foncée utilisée dans le thème du site',
            default => null,
        };
    }

    public static function isMaintenance(): bool
    {
        return self::get('under_maintenance') == 1;
    }
}
