<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings;

use Filament\Actions\EditAction;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Leobsst\LaravelCmsCore\Models\Setting;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Personnalisation';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $label = 'Paramètres';

    protected static ?int $navigationSort = 99;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getFormComponentForType($schema->getRecord()),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('setting_name')
                    ->searchable()
                    ->label('Nom')
                    ->tooltip(fn (Setting $record): ?string => $record->description),
                TextColumn::make('custom')
                    ->searchable(['value'])
                    ->label('Valeur')
                    ->toggleable()
                    ->default(fn ($record): mixed => match ($record->type) {
                        SettingTypeEnum::TAGS => $record->tags->pluck('name')->toArray(),
                        SettingTypeEnum::COLOR => new HtmlString('
                            <span style="border-radius: 100%; width: 1.5rem; height: 1.5rem; display: block; background-color: '.$record->value.';">&nbsp;</span>
                        '),
                        SettingTypeEnum::IMAGE => filled($record->value) ? new HtmlString('
                            <img src="'.$record->value.'" alt="'.$record->name.'" style="width: 3rem; height: 3rem; border-radius: 0.375rem;">
                        ') : null,
                        SettingTypeEnum::BOOLEAN => (bool) $record->value
                            ? new HtmlString('
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 2rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            ')
                            : new HtmlString('
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 2rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            '),
                        default => $record->value,
                    })
                    ->badge(fn (Setting $record): bool => $record->type === SettingTypeEnum::TAGS)
                    ->wrap(fn (Setting $record): bool => $record->type === SettingTypeEnum::TAGS)
                    ->color(fn (Setting $record): ?string => match ($record->type) {
                        SettingTypeEnum::BOOLEAN => (bool) $record->value ? 'success' : 'danger',
                        default => null
                    })
                    ->limit(fn (Setting $record): ?int => in_array($record->type, [
                        SettingTypeEnum::STRING,
                        SettingTypeEnum::TEXTAREA,
                    ]) ? 80 : null),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (Setting $record) => $record->type->title())
                    ->toggleable(),
                IconColumn::make('protected')
                    ->label('Protégé')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Modification du paramètre')
                    ->modalWidth('xl')
                    ->disabled(condition: fn (Setting $record): bool => $record->protected && ! auth()->user()->hasRole('admin'))
                    ->visible(fn (Setting $record): bool => $record->protected && ! auth()->user()->hasRole('admin')
                        ? false
                        : $record->enabled
                    ),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Setting $record) => ! $record->is_default)
            ->modifyQueryUsing(fn ($query) => $query->orderBy('name'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
        ];
    }

    private static function getFormComponentForType(Model $record): Component
    {
        return match ($record->type) {
            SettingTypeEnum::STRING => TextInput::make('value')
                ->label($record->setting_name),
            SettingTypeEnum::NUMBER => TextInput::make('value')
                ->label('Valeur')
                ->integer(),
            SettingTypeEnum::BOOLEAN => Toggle::make('value')
                ->label($record->setting_name)
                ->required(),
            SettingTypeEnum::JSON => Textarea::make('value')
                ->label($record->setting_name),
            SettingTypeEnum::DATE => DatePicker::make('value')
                ->label($record->setting_name),
            SettingTypeEnum::URL => TextInput::make('value')
                ->label($record->setting_name)
                ->url(),
            SettingTypeEnum::EMAIL => TextInput::make('value')
                ->label($record->setting_name)
                ->email()
                ->required(),
            SettingTypeEnum::TEXTAREA => $record->name === 'custom_css'
                ? CodeEditor::make('value')->label($record->setting_name)->language(Language::Css)
                : Textarea::make('value')->label($record->setting_name)->rows(10),
            SettingTypeEnum::COLOR => ColorPicker::make('value')
                ->label($record->setting_name)
                ->hexColor()
                ->required(),
            SettingTypeEnum::TAGS => SpatieTagsInput::make('tags')
                ->label($record->setting_name)
                ->required(),
            SettingTypeEnum::IMAGE => FileUpload::make('value')
                ->label('Bannière')
                ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png'])
                ->maxSize('5120')
                ->imageEditor()
                ->disk('assets')
                ->columnSpanFull()
                ->imageEditorAspectRatios(['16:9', '1:1'])
                ->imageEditorMode(3)
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1'),
            default => TextInput::make('value')
                ->label('Valeur')
                ->required(),
        };
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Valeur' => $record->value,
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('owner');
    }
}
