<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Leobsst\LaravelCmsCore\Filament\Tables\Columns\PageStatColumn;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Services\Features\PageService;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_path')
                    ->label('Slug')
                    ->searchable(['theme', 'slug'])
                    ->copyable()
                    ->sortable(['theme', 'slug']),
                ToggleColumn::make('is_published')
                    ->label('Publiée')
                    ->sortable()
                    ->tooltip('Si cette page est par défaut, elle n\'est pas désactivable.')
                    ->disabled(fn ($record) => $record->is_default)
                    ->toggleable(),
                IconColumn::make('is_default')
                    ->label('Défaut')
                    ->tooltip('Si cette page est par défaut, elle n\'est pas supprimable.')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                PageStatColumn::make('other')
                    ->label('Statistiques')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Dernière modification')
                    ->formatStateUsing(function ($record) {
                        return $record->updated_at->diffForHumans();
                    })
                    ->tooltip(fn ($record) => $record->updated_at->format('d/m/Y'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions(ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    self::getExportToJsonAction(),
                ])->dropdown(false),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->is_default),
            ])->button()->color('gray'))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('theme')
                    ->label('Thème')
                    ->relationship('theme', 'name')
                    ->placeholder('Tous les thèmes')
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make('is_published')
                    ->label('Publiée ?')
                    ->placeholder('Toutes'),
            ])
            ->modifyQueryUsing(
                fn ($query) => $query->with([
                    'theme',
                    'seo',
                    'stats',
                ])
            )
            ->checkIfRecordIsSelectableUsing(fn ($record) => ! $record->is_default);
    }

    private static function getExportToJsonAction(): Action
    {
        return Action::make('export_json')
            ->label('Exporter')
            ->icon('heroicon-o-document-text')
            ->action(function (Page $record) {
                $data = (new PageService($record))->exportToJson();
                $tmpFile = tempnam(sys_get_temp_dir(), 'page_');
                file_put_contents($tmpFile, $data);

                return response()->download($tmpFile, 'page-' . ($record->slug ?? 'home') . '.json', ['Content-Type' => 'application/json'])->deleteFileAfterSend(true);
            })
            ->visible(auth()->user()->hasRole('admin'));
    }
}
