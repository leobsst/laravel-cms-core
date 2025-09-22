<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MenusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Repeater::make('children')
                ->hiddenLabel()
                ->collapsible()
                ->addActionLabel('Ajouter un lien')
                ->maxItems(function ($record) {
                    if ($record->name === 'footer') {
                        return 9;
                    }

                    return 6;
                })
                ->relationship()
                ->orderColumn('order')
                ->grid([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 3,
                ])
                ->schema([
                    TextInput::make('name')
                        ->label('Nom')
                        ->required()
                        ->disabled(fn ($record) => $record->is_default ?? false),
                    Select::make('page_id')
                        ->label('Page')
                        ->relationship('page', 'title')
                        ->preload()
                        ->live()
                        ->searchable(fn (Get $get) => blank($get('url')))
                        ->placeHolder('Sélectionner une page')
                        ->disabled(fn ($record, Get $get) => (filled($record) && $record->is_default) || filled($get('url')))
                        ->hidden(fn ($record) => filled($record) && blank($record->page_id) && blank($record->url) && $record->children->count() > 0),
                    TextInput::make('url')
                        ->label('URL')
                        ->live()
                        ->disabled(fn ($record, Get $get) => (filled($record) && $record->is_default) || filled($get('page_id')))
                        ->hidden(fn ($record) => filled($record) && blank($record->page_id) && blank($record->url) && $record->children->count() > 0),
                    Repeater::make('children')
                        ->defaultItems(0)
                        ->hiddenLabel()
                        ->collapsed()
                        ->relationship(modifyQueryUsing: fn ($query) => $query->orderBy('order'))
                        ->orderColumn('order')
                        ->addActionLabel('Ajout un lien au sous-menu')
                        ->hidden(fn ($record) => filled($record) && (filled($record->page_id) || filled($record->url)) || $schema->getRecord()->name === 'footer')
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data) use ($schema): array {
                            $data['menu_id'] = $schema->getRecord()->id;

                            return $data;
                        })
                        ->schema([
                            TextInput::make('name')
                                ->label('Nom')
                                ->required(),
                            Select::make('page_id')
                                ->label('Page')
                                ->relationship('page', 'title')
                                ->preload()
                                ->live()
                                ->searchable(fn (Get $get) => blank($get('url')))
                                ->placeHolder('Sélectionner une page')
                                ->disabled(fn ($record, Get $get) => (filled($record) && $record->is_default) || filled($get('url'))),
                            TextInput::make('url')
                                ->label('URL')
                                ->live()
                                ->disabled(fn ($record, Get $get) => (filled($record) && $record->is_default) || filled($get('page_id'))),
                        ]),
                ]),
        ])
            ->columns(1);
    }
}
