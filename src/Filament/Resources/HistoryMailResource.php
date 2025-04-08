<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Models\HistoryMail;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Leobsst\LaravelCmsCore\Filament\Resources\HistoryMailResource\Pages\ListHistoryMails;

class HistoryMailResource extends Resource
{
    protected static ?string $model = HistoryMail::class;
    protected static ?string $navigationGroup = 'Historique';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $label = 'Historique des mails';
    protected static ?string $navigationLabel = 'Mails';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Sujet')
                    ->formatStateUsing(fn ($record) => mb_strimwidth($record->subject, 0, 80, '..'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->searchable()
                    ->sortable()
                    ->date('d/m/Y'),
            ])
            ->actions([
                ViewAction::make()->modalHeading(fn (HistoryMail $record) => $record->name . ' - ' . $record->created_at->format('d/m/Y')),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')
                        ->label('Créé entre le'),
                    DatePicker::make('created_until')
                        ->label('Et le'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->columns(2)
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderByDesc('created_at');
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHistoryMails::route('/'),
        ];
    }
}
