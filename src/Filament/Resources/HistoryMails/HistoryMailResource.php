<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leobsst\LaravelCmsCore\Models\HistoryMail;

class HistoryMailResource extends Resource
{
    protected static ?string $model = HistoryMail::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Historique';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $label = 'Historique des mails';

    protected static ?string $navigationLabel = 'Mails';

    public static function form(Schema $schema): Schema
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
            ->recordActions([
                ViewAction::make()->modalHeading(fn (HistoryMail $record) => $record->name.' - '.$record->created_at->format('d/m/Y')),
            ])
            ->filters([
                Filter::make('created_at')
                    ->schema([
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
                    ->columns(2),
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
            'index' => Pages\ListHistoryMails::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(roles: 'manager');
    }
}
