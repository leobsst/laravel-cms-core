<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\HistoryMails\Tables;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leobsst\LaravelCmsCore\Models\HistoryMail;

class HistoryMailsTable
{
    public static function configure(Table $table): Table
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
                ViewAction::make()->modalHeading(fn (HistoryMail $record) => $record->name . ' - ' . $record->created_at->format('d/m/Y')),
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
}
