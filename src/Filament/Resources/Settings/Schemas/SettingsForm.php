<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getFormComponentForType($schema->getRecord(), true),
                self::getFormComponentForType($schema->getRecord()),
            ])->columns(1);
    }

    private static function getFormComponentForType(Model $record, bool $default = false): Component
    {
        $property = $default ? 'default_value' : 'value';
        $label = $default ? 'Valeur par défaut' : $record->setting_name;

        return match ($record->type) {
            FieldTypeEnum::STRING => TextInput::make($property)
                ->label($label)
                ->disabled($default),
            FieldTypeEnum::NUMBER => TextInput::make($property)
                ->label('Valeur')
                ->integer()
                ->disabled($default),
            FieldTypeEnum::BOOLEAN => Toggle::make($property)
                ->label($label)
                ->required()
                ->disabled($default),
            FieldTypeEnum::JSON => Textarea::make($property)
                ->label($label)
                ->disabled($default),
            FieldTypeEnum::DATE => DatePicker::make($property)
                ->label($label)
                ->disabled($default),
            FieldTypeEnum::URL => TextInput::make($property)
                ->label($label)
                ->url()
                ->disabled($default),
            FieldTypeEnum::EMAIL => TextInput::make($property)
                ->label($label)
                ->email()
                ->disabled($default)
                ->required(),
            FieldTypeEnum::TEXTAREA => $record->name === 'custom_css'
                ? CodeEditor::make($property)->label($label)->language(Language::Css)->disabled($default)
                : Textarea::make($property)->label($label)->rows(10)->disabled($default),
            FieldTypeEnum::COLOR => ColorPicker::make($property)
                ->label($label)
                ->hexColor()
                ->disabled($default)
                ->required(),
            FieldTypeEnum::TAGS => SpatieTagsInput::make('tags')
                ->label($label)
                ->required(),
            FieldTypeEnum::IMAGE => FileUpload::make($property)
                ->label($default ? 'Bannière par défaut' : 'Bannière')
                ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/webp', 'image/gif', 'image/png'])
                ->maxSize('5120')
                ->imageEditor()
                ->disk('assets')
                ->columnSpanFull()
                ->imageEditorAspectRatios(['16:9', '1:1'])
                ->imageEditorMode(3)
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->disabled($default),
            default => TextInput::make($property)
                ->label($default ? 'Valeur par défaut' : 'Valeur')
                ->disabled($default)
                ->required(),
        };
    }
}
