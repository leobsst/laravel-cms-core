<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageTheme;

class PagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->persistTabInQueryString()
                    ->tabs([
                        self::getContentTab(),
                        self::getMetadataTab(),
                        self::getBannerTab(),
                        self::getGalleryTab(),
                        self::getAdvancedDataTab(),
                    ])->columnSpanFull()->contained(false),
            ]);
    }

    private static function getContentTab(): Tab
    {
        return Tab::make('Contenu')
            ->icon('heroicon-o-document-text')
            ->hidden(fn ($record) => filled($record) && $record->no_content)
            ->schema([
                TinyEditor::make('content')
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->columnSpan('full')
                    ->fileAttachmentsDirectory('pages/content')
                    ->minHeight(720)
                    ->profile('custom')
                    ->showMenuBar(),
            ]);
    }

    private static function getMetadataTab(): Tab
    {
        return Tab::make('Informations générales')
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
                                ->placeholder('Thème')
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
                            ->label('Thème (dossier) / Slug*')
                            ->hidden(fn ($record) => filled($record) && $record->is_default),
                    ])->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 1,
                        'xl' => 2,
                    ]),
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
            ]);
    }

    private static function getBannerTab(): Tab
    {
        return Tab::make('Bannière')
            ->icon('heroicon-o-photo')
            ->schema([
                Grid::make()
                    ->schema([
                        FileUpload::make('banner')
                            ->label('Bannière')
                            ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png'])
                            ->maxSize(5120)
                            ->imageEditor()
                            ->directory('pages/banners')
                            ->columnSpanFull()
                            ->imageEditorAspectRatios(['16:9'])
                            ->imageEditorMode(3)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9'),
                    ]),
            ]);
    }

    private static function getGalleryTab(): Tab
    {
        return Tab::make('Galeries')
            ->icon('heroicon-o-rectangle-stack')
            ->schema([
                Section::make('Conseil')
                    ->icon('heroicon-o-light-bulb')
                    ->iconColor('yellow')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('advice')
                            ->hiddenLabel()
                            ->content(new HtmlString("
                                Pour insérer une galerie d'images dans le contenu de votre page,<br>
                                insérez le shortcode correspondant dans le contenu, par exemple : <strong>[[gallery:identifiant_de_la_galerie]]</strong>
                            "))
                            ->columnSpanFull(),
                    ]),
                Repeater::make('galleries')
                    ->relationship('galleries')
                    ->hiddenLabel()
                    ->columns(2)
                    ->createItemButtonLabel('Ajouter une galerie')
                    ->itemLabel(fn ($state): ?string => $state['identifier'] ?? null)
                    ->defaultItems(0)
                    ->collapsible()
                    ->schema([
                        TextInput::make('identifier')
                            ->label('Identifiant')
                            ->required()
                            ->prefix('[[gallery:')
                            ->suffix(']]')
                            ->placeholder('Identifiant de la galerie')
                            ->maxLength(100)
                            ->unique(table: 'page_galleries', column: 'identifier', ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('page_id', request()->route('record')))
                            ->copyable(copyMessage: 'Identifiant copié !'),
                        Select::make('orientation')
                            ->label('Orientation')
                            ->disablePlaceholderSelection()
                            ->options(PageGalleryOrientation::asSelectArray())
                            ->default(PageGalleryOrientation::HORIZONTAL)
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Images')
                            ->multiple()
                            ->enableReordering()
                            ->collection('images')
                            ->downloadable()
                            ->imageEditor()
                            ->imageEditorAspectRatios([null, '16:9', '1:1'])
                            ->imageEditorMode(3)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    private static function getAdvancedDataTab(): Tab
    {
        return Tab::make('Avancé')
            ->icon('heroicon-o-cog')
            ->visible(fn (?Page $record): bool => self::canViewAdditionalDataTab($record))
            ->schema([
                Repeater::make('options')
                    ->relationship('options')
                    ->hiddenLabel()
                    ->columns(2)
                    ->createItemButtonLabel('Ajouter une donnée')
                    ->addable(auth()->user()->hasRole('admin'))
                    ->deletable(auth()->user()->hasRole('admin'))
                    ->itemLabel(fn ($state): ?string => $state['name'] ?? null)
                    ->defaultItems(0)
                    ->collapsible()
                    ->schema([
                        TextInput::make('key')
                            ->label('Clé')
                            ->required()
                            ->placeholder('Clé')
                            ->disabled(! auth()->user()->hasRole('admin'))
                            ->unique(table: 'page_options', column: 'key', ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('page_id', request()->route('record')))
                            ->maxLength(45),
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->disabled(! auth()->user()->hasRole('admin'))
                            ->maxLength(100),
                        Select::make('type')
                            ->label('Type')
                            ->required()
                            ->disablePlaceholderSelection()
                            ->default(FieldTypeEnum::STRING->value)
                            ->options(FieldTypeEnum::asSelectArray(['password', 'tags', 'json', 'serialized']))
                            ->default('string')
                            ->live()
                            ->disabled(! auth()->user()->hasRole('admin'))
                            ->afterStateUpdated(function (Select $component, string $state, Get $get) {
                                $component = $component
                                    ->getContainer()
                                    ->getComponent('dynamicTypeFields')
                                    ->getChildSchema();

                                if ($state === FieldTypeEnum::IMAGE->value) {
                                    $component = $component->fill();
                                } elseif (is_array($get('value'))) {
                                    $component = $component->fill();
                                }

                                return $component;
                            }),
                        Grid::make(1)
                            ->schema(fn (Get $get): array => [
                                self::getFormComponentForType($get('type')),
                            ])
                            ->key('dynamicTypeFields')
                            ->hidden(fn (Get $get) => blank($get('type')))
                            ->columnSpan(2),
                    ])->columnSpanFull(),
            ]);
    }

    private static function canViewAdditionalDataTab(?Page $record = null): bool
    {
        return match (true) {
            blank($record) && auth()->user()->hasRole('admin') => true,
            filled($record) && blank($record->options) && auth()->user()->hasRole('admin') => true,
            filled($record) && filled($record->options) => true,
            default => false,
        };
    }

    private static function getFormComponentForType(?string $type = null): Component
    {
        return match ($type) {
            FieldTypeEnum::STRING->value => TextInput::make('value')
                ->label('Valeur'),
            FieldTypeEnum::NUMBER->value => TextInput::make('value')
                ->label('Valeur')
                ->integer(),
            FieldTypeEnum::BOOLEAN->value => Toggle::make('value')
                ->label('Valeur'),
            FieldTypeEnum::JSON->value => Textarea::make('value')
                ->label('Valeur'),
            FieldTypeEnum::DATE->value => DatePicker::make('value')
                ->label('Valeur'),
            FieldTypeEnum::URL->value => TextInput::make('value')
                ->label('Valeur')
                ->url(),
            FieldTypeEnum::EMAIL->value => TextInput::make('value')
                ->label('Valeur')
                ->email(),
            FieldTypeEnum::TEXTAREA->value => Textarea::make('value')
                ->label('Valeur')
                ->rows(10),
            FieldTypeEnum::COLOR->value => ColorPicker::make('value')
                ->label('Valeur')
                ->hexColor(),
            FieldTypeEnum::TAGS->value => SpatieTagsInput::make('value')
                ->label('Valeur'),
            FieldTypeEnum::IMAGE->value => FileUpload::make('value')
                ->label('Valeur')
                ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png'])
                ->maxSize('5120')
                ->imageEditor()
                ->disk('assets')
                ->columnSpanFull()
                ->imageEditorAspectRatios(['16:9', '1:1'])
                ->imageEditorMode(3)
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1'),
            FieldTypeEnum::RANGE_INT->value => Slider::make('value')
                ->label('Valeur')
                ->range(0, 100)
                ->tooltips()
                ->step(1),
            FieldTypeEnum::RANGE_FLOAT->value => Slider::make('value')
                ->label('Valeur')
                ->range(0, 1)
                ->tooltips()
                ->decimalPlaces(1)
                ->step(0.1),
            default => TextInput::make('value')
                ->label('Valeur'),
        };
    }
}
