<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Pennant\Feature;
use Leobsst\LaravelCmsCore\Filament\Tables\Columns\PageStatColumn;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageTheme;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Publications';

    protected static ?string $label = 'Mes pages';

    protected static ?string $navigationLabel = 'Pages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Informations générales')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Nom')
                                            ->required(fn ($record) => ! $record?->is_default)
                                            ->placeholder('Nom de la page')
                                            ->maxLength(45)
                                            ->disabled(fn ($record) => $record->is_default ?? false),
                                        FusedGroup::make([
                                            Select::make('theme_id')
                                                ->relationship('theme', 'name')
                                                ->hiddenLabel()
                                                ->disabled(fn ($record) => $record->is_default ?? false)
                                                ->searchable()
                                                ->createOptionForm([
                                                    TextInput::make('name')
                                                        ->required()
                                                        ->placeholder('Thème (dossier public)')
                                                        ->maxLength(45)
                                                        ->unique('page_themes', 'name', ignoreRecord: true)
                                                        ->regex('/^[a-zA-Z0-9-_]+$/')
                                                        ->notIn(PageTheme::FORBIDDEN_VALUES)
                                                        ->validationMessages([
                                                            'unique' => 'Ce thème existe déjà.',
                                                            'regex' => 'Le thème ne peut contenir que des lettres, chiffres, tirets et underscores.',
                                                        ]),
                                                ]),
                                            TextInput::make('slug')
                                                ->hiddenLabel()
                                                ->required(fn ($record) => ! $record?->is_default)
                                                ->placeholder(fn ($record) => filled($record) && $record->is_default ? '' : 'Slug de la page')
                                                ->disabled(fn ($record) => $record->is_default ?? false)
                                                ->unique('pages', 'slug', ignoreRecord: true)
                                                ->validationMessages([
                                                    'unique' => 'Ce slug est déjà utilisé.',
                                                ]),
                                        ])
                                            ->columns(2)
                                            ->label('Slug'),
                                    ])->columns(2),
                                Section::make('SEO')
                                    ->relationship('seo')
                                    ->schema([
                                        SpatieTagsInput::make('tags')
                                            ->label('Mot-clés')
                                            ->placeholder('Ajouter des mots-clés')
                                            ->splitKeys([' '])
                                            ->required(),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->placeholder('Description de la page')
                                            ->rows(3)
                                            ->required(),
                                    ])->Columns(2),
                                Toggle::make('is_published')
                                    ->label('Publiée ?')
                                    ->default(true)
                                    ->columnSpanFull()
                                    ->disabled(fn ($record) => $record->is_default ?? false),
                            ]),
                        Tab::make('Bannière')
                            ->hidden(fn ($record) => filled($record) && $record->is_home)
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        FileUpload::make('banner')
                                            ->label('Bannière')
                                            ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png'])
                                            ->maxSize('5120')
                                            ->imageEditor()
                                            ->disk('uploads')
                                            ->columnSpanFull()
                                            ->imageEditorAspectRatios(['16:9'])
                                            ->imageEditorMode(3)
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('16:9'),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
                RichEditor::make('content')
                    ->hiddenLabel()
                    ->required()
                    ->fileAttachmentsDisk('uploads')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_path')
                    ->label('Slug')
                    ->searchable(['theme', 'slug'])
                    ->copyable()
                    ->sortable(['theme', 'slug']),
                ToggleColumn::make('is_published')
                    ->label('Publiée')
                    ->sortable()
                    ->tooltip('Si cette page est par défaut, elle n\'est pas désactivable.')
                    ->disabled(fn ($record) => $record->is_default)
                    ->toggleable(),
                IconColumn::make('is_default')
                    ->label('Défaut')
                    ->tooltip('Si cette page est par défaut, elle n\'est pas supprimable.')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                PageStatColumn::make('other')
                    ->label('Statistiques')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Dernière modification')
                    ->formatStateUsing(function ($record) {
                        return $record->updated_at->diffForHumans();
                    })
                    ->tooltip(fn ($record) => $record->updated_at->format('d/m/Y'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions(ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->is_default),
            ])->button()->color('gray'))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('theme')
                    ->label('Thème')
                    ->relationship('theme', 'name')
                    ->placeholder('Tous les thèmes')
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make('is_published')
                    ->label('Publiée ?')
                    ->placeholder('Toutes'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with([
                'theme',
                'seo',
                'stats',
            ])
            )
            ->checkIfRecordIsSelectableUsing(fn ($record) => ! $record->is_default);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'slug' => $record->slug,
            'courte description' => mb_strimwidth($record->content, 0, 100, '...'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('editor') && Feature::active('pages');
    }
}
