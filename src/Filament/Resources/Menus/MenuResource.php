<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Menus;

use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravel\Pennant\Feature;
use Leobsst\LaravelCmsCore\Models\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Personnalisation';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $title = 'Menus';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('childrens')
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
                            ->hidden(fn ($record) => filled($record) && blank($record->page_id) && blank($record->url) && $record->childrens->count() > 0),
                        TextInput::make('url')
                            ->label('URL')
                            ->live()
                            ->disabled(fn ($record, Get $get) => (filled($record) && $record->is_default) || filled($get('page_id')))
                            ->hidden(fn ($record) => filled($record) && blank($record->page_id) && blank($record->url) && $record->childrens->count() > 0),
                        Repeater::make('childrens')
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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'edit' => Pages\EditMenu::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('manager') && Feature::active('menus');
    }
}
