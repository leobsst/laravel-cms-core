<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Logs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Leobsst\LaravelCmsCore\Models\User;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class LogsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                DateTimePicker::make(name: 'created_at')
                    ->format(format: 'DD/MM/YYYY HH:mm:ss')
                    ->label(label: 'Créé le'),
                DateTimePicker::make(name: 'updated_at')
                    ->format(format: 'DD/MM/YYYY HH:mm:ss')
                    ->label(label: 'Finalisé le'),
                Select::make(name: 'creator_id')
                    ->options(options: User::selectRaw('CONCAT(name, " (", email, ")") AS name, id')->pluck('name', 'id'))
                    ->label(label: 'Créateur'),
                Select::make(name: 'type')
                    ->options(options: [
                        'info' => 'Information',
                        'warning' => 'Avertissement',
                        'error' => 'Erreur',
                        'success' => 'Succès',
                        'debug' => 'Débogage',
                        'critical' => 'Critique',
                        'alert' => 'Alerte',
                        'emergency' => 'Urgence',
                        'cron' => 'CRON',
                    ])
                    ->label(label: 'Type'),
                Select::make(name: 'status')
                    ->options(options: [
                        'success' => 'Succès',
                        'error' => 'Erreur',
                        'running' => 'En cours',
                        'pending' => 'En attente',
                    ])
                    ->label(label: 'Statut'),
                TextInput::make(name: 'ip_address')
                    ->label(label: 'IP'),
                TextInput::make(name: 'reference_table')
                    ->label(label: 'Table'),
                TextInput::make(name: 'reference_id')
                    ->label(label: 'ID'),
                Textarea::make(name: 'message')
                    ->label(label: 'Message')
                    ->columnSpanFull(),
                JsonColumn::make(name: 'data')
                    ->label(label: 'Informations supplémentaires')
                    ->hidden(condition: fn ($state): bool => blank(value: $state))
                    ->viewerOnly()
                    ->columnSpanFull(),
            ])->disabled();
    }
}
