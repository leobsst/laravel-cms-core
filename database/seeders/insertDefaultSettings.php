<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
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
                'type' => FieldTypeEnum::URL,
                'protected' => true,
            ],
            [
                'name' => 'website_logo',
                'value' => null,
                'enabled' => false,
                'type' => FieldTypeEnum::IMAGE,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'website_header_image',
                'value' => null,
                'enabled' => false,
                'type' => FieldTypeEnum::IMAGE,
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
                'type' => FieldTypeEnum::STRING,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'long',
                'value' => null,
                'type' => FieldTypeEnum::STRING,
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
                'type' => FieldTypeEnum::EMAIL,
                'category' => SettingCategoryEnum::CONTACT,
            ],
            [
                'name' => 'primary_color',
                'value' => '#e3903e',
                'type' => FieldTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'primary_color_dark',
                'value' => '#de6849',
                'type' => FieldTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'business_entity_enabled',
                'value' => 0,
                'type' => FieldTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::GENERAL,
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
                'type' => FieldTypeEnum::EMAIL,
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
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'twitter',
                'value' => null,
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'instagram',
                'value' => null,
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'youtube',
                'value' => null,
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'twitch',
                'value' => null,
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'github',
                'value' => null,
                'type' => FieldTypeEnum::URL,
                'category' => SettingCategoryEnum::SOCIAL,
            ],
            [
                'name' => 'custom_css',
                'value' => null,
                'type' => FieldTypeEnum::TEXTAREA,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'under_maintenance',
                'value' => 1,
                'type' => FieldTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'alert_box_message',
                'value' => null,
                'type' => FieldTypeEnum::TEXTAREA,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'alert_box_enabled',
                'value' => 0,
                'type' => FieldTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
            ],
            [
                'name' => 'website_closed',
                'value' => 0,
                'type' => FieldTypeEnum::BOOLEAN,
                'category' => SettingCategoryEnum::SECURITY,
                'protected' => true,
            ],
        ];

        foreach ($defaultConfigurations as $configuration) {
            if ($configuration['name'] === 'website_keywords') {
                Setting::updateOrCreate([
                    'name' => $configuration['name'],
                ], [
                    'type' => FieldTypeEnum::TAGS,
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
                    'default_value' => array_key_exists('default_value', $configuration) ? $configuration['default_value'] : $configuration['value'], // @phpstan-ignore-line
                    'type' => $configuration['type'] ?? FieldTypeEnum::STRING,
                    'enabled' => $configuration['enabled'] ?? true,
                    'category' => $configuration['category'] ?? SettingCategoryEnum::GENERAL,
                    'is_default' => true,
                    'protected' => $configuration['protected'] ?? false,
                ]);
            }
        }
    }
}
