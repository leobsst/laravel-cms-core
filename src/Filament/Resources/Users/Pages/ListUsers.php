<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Leobsst\LaravelCmsCore\Filament\Resources\Users\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(mb_strtoupper('Créer'))
                ->modalWidth('md')
                ->using(fn (array $data): Model => self::getModel()::create(array_merge($data, ['password' => bcrypt(Str::random(16))])))
                ->after(fn ($record) => $record->sendFinalizationEmail()),
        ];
    }
}
