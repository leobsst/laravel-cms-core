<?php

namespace Database\Seeders;

use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Illuminate\Database\Seeder;

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
                'type' => SettingTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
            ],
            [
                'name' => 'primary_color_dark',
                'value' => '#de6849',
                'type' => SettingTypeEnum::COLOR,
                'category' => SettingCategoryEnum::CUSTOMIZATION,
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
            ]
        ];

        foreach ($defaultConfigurations as $configuration) {
            if ($configuration['name'] === 'website_keywords') {
                Setting::firstOrCreate([
                    'name' => $configuration['name']
                ], [
                    'type' => SettingTypeEnum::TAGS,
                    'category' => SettingCategoryEnum::GENERAL,
                    'is_default' => true,
                    'tags' => [
                        0 => 'LEOBSST',
                    ],
                ]);
            } else {
                Setting::firstOrCreate([
                    'name' => $configuration['name']
                ], [
                    'value' => $configuration['value'],
                    'type' => $configuration['type'] ?? SettingTypeEnum::STRING,
                    'enabled' => $configuration['enabled'] ?? true,
                    'category' => $configuration['category'] ?? SettingCategoryEnum::GENERAL,
                    'is_default' => true,
                ]);
            }
        }
    }
}
