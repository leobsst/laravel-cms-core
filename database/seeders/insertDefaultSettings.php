<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Leobsst\LaravelCmsCore\Models\Setting;

class insertDefaultSettings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultConfigurations = [
            [
                'name' => 'website_name',
                'value' => 'Laravel project',
            ],
            [
                'name' => 'website_url',
                'value' => 'https://localhost.test',
                'type' => SettingTypeEnum::URL,
                'protected' => true,
            ],
            [
                'name' => 'website_logo',
                'value' => null,
                'enabled' => false,
                'type' => SettingTypeEnum::IMAGE,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'website_header_image',
                'value' => null,
                'enabled' => false,
                'type' => SettingTypeEnum::IMAGE,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'website_description',
                'value' => null,
            ],
            [
                'name' => 'website_keywords',
                'is_default' => true,
            ],
            [
                'name' => 'address',
                'value' => null,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'city',
                'value' => null,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'zip',
                'value' => null,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'country',
                'value' => null,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'lat',
                'value' => null,
                'type' => SettingTypeEnum::NUMBER,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'long',
                'value' => null,
                'type' => SettingTypeEnum::NUMBER,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'phone_number',
                'value' => null,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'email_address',
                'value' => in_array(config('app.env'), ['dev', 'local']) ? 'support@leobsst.fr' : 'support@leobsst.fr',
                'type' => SettingTypeEnum::EMAIL,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'primary_color',
                'value' => '#e3903e',
                'default_value' => '#e3903e',
                'type' => SettingTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'primary_color_dark',
                'value' => '#de6849',
                'default_value' => '#de6849',
                'type' => SettingTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'business_entity_enabled',
                'value' => 0,
                'type' => SettingTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'business_entity_name',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_siret',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_vat_number',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_address',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_city',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_zip',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_country',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_phone_number',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_email_address',
                'value' => null,
                'type' => SettingTypeEnum::EMAIL,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'business_entity_owner',
                'value' => null,
                'category' => SettingCategoryEnum::BUSINESS,
            ],
            [
                'name' => 'facebook',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'twitter',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'instagram',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'youtube',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'twitch',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'github',
                'value' => null,
                'type' => SettingTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'custom_css',
                'value' => null,
                'type' => SettingTypeEnum::TEXTAREA,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'under_maintenance',
                'value' => 1,
                'type' => SettingTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'alert_box_message',
                'value' => null,
                'type' => SettingTypeEnum::TEXTAREA,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'alert_box_enabled',
                'value' => 0,
                'type' => SettingTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'website_closed',
                'value' => 0,
                'type' => SettingTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
                'protected' => true,
            ],
        ];

        foreach ($defaultConfigurations as $configuration) {
            if ($configuration['name'] === 'website_keywords') {
                Setting::updateOrCreate([
                    'name' => $configuration['name'],
                ], [
                    'type' => SettingTypeEnum::TAGS,
                    'category' => SettingCategoryEnum::GENERAL,
                    'is_default' => true,
                    'tags' => [
                        0 => 'LEOBSST',
                        1 => 'B.L.A.M. PRODUCTION',
                    ],
                ]);
            } else {
                DB::table('settings')->updateOrInsert([
                    'name' => $configuration['name'],
                ], [
                    'value' => $configuration['value'],
                    'default_value' => $configuration['default_value'] ?? $configuration['value'],
                    'type' => $configuration['type'] ?? SettingTypeEnum::STRING,
                    'enabled' => $configuration['enabled'] ?? true,
                    'category' => $configuration['category'] ?? SettingCategoryEnum::GENERAL,
                    'is_default' => true,
                    'protected' => $configuration['protected'] ?? false,
                ]);
            }
        }
    }
}
