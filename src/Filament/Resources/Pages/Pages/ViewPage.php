<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Leobsst\LaravelCmsCore\Filament\Resources\Pages\PageResource;
use Leobsst\LaravelCmsCore\Filament\Widgets\Last30DaysVisit;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(mb_strtoupper('Modifier')),
            DeleteAction::make()
                ->hidden(fn () => $this->record->is_default)
                ->label(mb_strtoupper('Supprimer')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            Last30DaysVisit::class,
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->title;
    }
}
