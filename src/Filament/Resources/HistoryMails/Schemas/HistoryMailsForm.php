<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HistoryMailsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom'),
                TextInput::make('email')->email(),
                TextInput::make('phone')
                    ->label('Téléphone'),
                TextInput::make('subject')
                    ->label('Sujet')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->label('Message')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
