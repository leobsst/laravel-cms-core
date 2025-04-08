<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\SettingResource\Pages;

use Leobsst\LaravelCmsCore\Enums\SettingCategoryEnum;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Leobsst\LaravelCmsCore\Filament\Resources\SettingResource;

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
