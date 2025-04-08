<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Leobsst\LaravelCmsCore\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(mb_strtoupper('Créer'))
                ->modalWidth('md')
                ->using(fn (array $data): Model => self::getModel()::create(array_merge($data, ['password' => bcrypt(Str::random(16))])))
                ->after(fn ($record) => $record->sendFinalizationEmail()),
        ];
    }
}
