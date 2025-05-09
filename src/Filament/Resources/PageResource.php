<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources;

use Filament\Forms;
use Leobsst\LaravelCmsCore\Models\Page;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Leobsst\LaravelCmsCore\Filament\Tables\Columns\PageStatColumn;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages\EditPage;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages\ViewPage;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages\ListPages;
use Leobsst\LaravelCmsCore\Filament\Resources\PageResource\Pages\CreatePage;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationGroup = 'Publications';
    protected static ?string $label = 'Mes pages';
    protected static ?string $navigationLabel = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('Informations générales')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Nom')
                                            ->required(fn($record) => !$record?->is_default)
                                            ->placeholder('Nom de la page')
                                            ->maxLength(45)
                                            ->disabled(fn($record) => $record->is_default ?? false),
                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug')
                                            ->required(fn($record) => !$record?->is_default)
                                            ->placeholder(fn($record) => filled($record) && $record->is_default ? '' : 'Slug de la page')
                                            ->disabled(fn($record) => $record->is_default ?? false)
                                            ->unique('pages', 'slug', ignoreRecord: true)
                                            ->validationMessages([
                                                'unique' => 'Ce slug est déjà utilisé.',
                                            ]),
                                    ])->columns(2),
                                Section::make('SEO')
                                    ->relationship('seo')
                                    ->schema([
                                        Forms\Components\SpatieTagsInput::make('tags')
                                            ->label('Mot-clés')
                                            ->placeholder('Ajouter des mots-clés')
                                            ->splitKeys([' '])
                                            ->required(),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Description')
                                            ->placeholder('Description de la page')
                                            ->rows(3)
                                            ->required(),
                                    ])->Columns(2),
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publiée ?')
                                    ->default(true)
                                    ->columnSpanFull()
                                    ->disabled(fn($record) => $record->is_default ?? false),
                            ]),
                        Tab::make('Bannière')
                            ->hidden(fn ($record) => filled($record) && $record->is_home)
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\FileUpload::make('banner')
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
                Section::make('Contenu')
                    ->schema([
                        TinyEditor::make('content')
                            ->hiddenLabel()
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('uploads')
                            ->profile('custom')
                            ->columnSpan('full')
                            ->showMenuBar(),
                    ])->columns(1)->collapsible(),
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
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label('Publiée')
                    ->sortable()
                    ->tooltip('Si cette page est par défaut, elle n\'est pas désactivable.')
                    ->disabled(fn($record) => $record->is_default)
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
                    ->tooltip(fn($record) => $record->updated_at->format('d/m/Y'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions(ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn($record) => $record->is_default),
            ])->button()->color('gray'))
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(fn ($record) => !$record->is_default);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => ViewPage::route('/{record}'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
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

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('editor');
    }
}
