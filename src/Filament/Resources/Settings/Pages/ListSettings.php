<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Leobsst\LaravelCmsCore\Filament\Resources\Settings\SettingResource;
use Leobsst\LaravelCmsCore\Models\Setting;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public function getTabs(): array
    {
        return [
            'general' => Tab::make('Divers')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::GENERAL);
                }),
            'customization' => Tab::make('Personnalisation')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::CUSTOMIZATION);
                }),
            'contact' => Tab::make('Contact')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::CONTACT);
                }),
            'business' => Tab::make('Entreprise')
                ->hidden(condition: Setting::get('business_entity_enabled') == 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::BUSINESS);
                }),
            'social' => Tab::make('Réseaux sociaux')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::SOCIAL);
                }),
            // DISABLED FOR NOW
            // 'payment' => Tab::make('Paiement')
            //     ->modifyQueryUsing(function ($query) {
            //         return $query->where('category', SettingCategoryEnum::PAYMENT);
            //     }),
            'security' => Tab::make('Administration')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('category', SettingCategoryEnum::SECURITY);
                }),
        ];
    }
}
