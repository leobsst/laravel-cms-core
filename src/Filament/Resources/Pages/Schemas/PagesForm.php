<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageTheme;

class PagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Contenu')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TinyEditor::make('content')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->columnSpan('full')
                                    ->fileAttachmentsDirectory('pages/content')
                                    ->minHeight(720)
                                    ->profile('custom')
                                    ->showMenuBar(),
                            ]),
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
                                            ->label('Thème (dossier) / Slug*'),
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
                                            ->directory('pages/banners')
                                            ->columnSpanFull()
                                            ->imageEditorAspectRatios(['16:9'])
                                            ->imageEditorMode(3)
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('16:9'),
                                    ]),
                            ]),
                    ])->columnSpanFull()->contained(false),
            ]);
    }
}
