<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources;

use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Enums\SettingTypeEnum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\SpatieTagsInput;
use Leobsst\LaravelCmsCore\Filament\Tables\Columns\SettingTypeColumn;
use Leobsst\LaravelCmsCore\Filament\Resources\SettingResource\Pages\ListSettings;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationGroup = 'Personnalisation';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $label = 'Paramètres';
    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getFormComponentForType($form->getRecord()),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->tooltip(fn($record) => $record->getSettingName())
                    ->searchable()
                    ->label('Nom'),
                SettingTypeColumn::make('value')
                    ->searchable()
                    ->label('Valeur')
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->type->title())
                    ->toggleable(),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Modification du paramètre')
                    ->modalWidth('xl')
                    ->visible(fn ($record) => $record->enabled),
            ])
            ->checkIfRecordIsSelectableUsing(fn ($record) => !$record->is_default)
            ->modifyQueryUsing(fn ($query) => $query->orderBy('name'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettings::route('/'),
        ];
    }

    private static function getFormComponentForType(Model $record): Component
    {
        return match ($record->type) {
            SettingTypeEnum::STRING => TextInput::make('value')
                ->label($record->getSettingName()),
            SettingTypeEnum::NUMBER => TextInput::make('value')
                ->label('Valeur')
                ->integer(),
            SettingTypeEnum::BOOLEAN => Toggle::make('value')
                ->label($record->getSettingName())
                ->required(),
            SettingTypeEnum::JSON => Textarea::make('value')
                ->label($record->getSettingName()),
            SettingTypeEnum::DATE => DatePicker::make('value')
                ->label($record->getSettingName()),
            SettingTypeEnum::URL => TextInput::make('value')
                ->label($record->getSettingName())
                ->url(),
            SettingTypeEnum::EMAIL => TextInput::make('value')
                ->label($record->getSettingName())
                ->email()
                ->required(),
            SettingTypeEnum::TEXTAREA => Textarea::make('value')
                ->label($record->getSettingName())
                ->rows(10),
            SettingTypeEnum::COLOR => ColorPicker::make('value')
                ->label($record->getSettingName())
                ->hexColor()
                ->required(),
            SettingTypeEnum::TAGS => SpatieTagsInput::make('tags')
                ->label($record->getSettingName())
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

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
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
