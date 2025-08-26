<?php

namespace Leobsst\LaravelCmsCore\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

/**
 * Class Page
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property SettingTypeEnum $type
 * @property SettingCategoryEnum $category
 * @property bool $is_default
 * @property bool $enabled
 * @property Tag[] $tags
 */
class Setting extends Model
{
    use HasTags;

    protected $fillable = [
        'name',
        'value',
        'type',
        'category',
        'is_default',
        'enabled',
    ];

    protected $casts = [
        'type' => SettingTypeEnum::class,
        'category' => SettingCategoryEnum::class,
        'is_default' => 'boolean',
        'enabled' => 'boolean',
    ];

    /*
    * Set website name
    */
    public static function set(string $parameter, mixed $value): bool|Exception
    {
        return self::where('name', $parameter)->firstOrFail()->update(['value' => $value]);
    }

    /*
    *  Get website name
    *  @return null|string|Exception
    */
    public static function get(string $parameter): null|string|Exception
    {
        return self::where('name', $parameter)->firstOrFail()->value;
    }

    /**
     * Get website tags
     */
    public function getWebsiteTagsAttribute(): string
    {
        $tags = [];
        self::firstWhere('name', 'website_keywords')->tags->each(function ($tag) use (&$tags) {
            $tags[] = $tag->name;
        });

        return implode(', ', $tags);
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
            default => $this->name,
        };
    }

    public static function isMaintenance(): bool
    {
        return self::get('under_maintenance') == 1;
    }
}
