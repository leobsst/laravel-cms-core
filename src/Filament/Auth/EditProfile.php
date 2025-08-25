<?php

namespace Leobsst\LaravelCmsCore\Filament\Auth;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getAdditionalEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function getAdditionalEmailFormComponent(): Component
    {
        return Repeater::make('emails')
            ->label('Adresses Email secondaires')
            ->relationship('emails', modifyQueryUsing: fn ($query) => $query->where('email', '!=', $this->getUser()->email))
            ->columns(1)
            ->minItems(0)
            ->schema([
                TextInput::make('email')
                    ->hiddenLabel()
                    ->email()
                    ->unique(table: 'user_emails', column: 'email', ignoreRecord: true),
            ])
            ->addActionLabel(label: 'Ajouter une adresse Email');
    }
}
