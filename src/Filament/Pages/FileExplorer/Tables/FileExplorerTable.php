<?php

namespace Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Leobsst\LaravelCmsCore\Concerns\Features\FileExplorer\CanNavigateThroughFileExplorer;
use Leobsst\LaravelCmsCore\Services\Features\FileExplorerService;
use Livewire\Component;

class FileExplorerTable
{
    use CanNavigateThroughFileExplorer;

    public static function configure(Table $table): Table
    {
        return $table
            ->records(
                function (?string $search, Component $livewire): Collection {
                    /** @var CanNavigateThroughFileExplorer&Component $livewire */
                    $disk = $livewire->getCurrentDisk();
                    $path = $livewire->getCurrentDirectoryPath();

                    return self::getRecords(
                        disk: $disk,
                        path: $path,
                        search: $search,
                    );
                }
            )
            ->recordAction(function ($record) {
                $type = is_array($record) ? ($record['type'] ?? null) : ($record->type ?? null);

                return $type === 'file' ? null : 'see';
            })
            ->recordUrl(function ($record) {
                $type = is_array($record) ? ($record['type'] ?? null) : ($record->type ?? null);
                if ($type !== 'file') {
                    return null;
                }

                $disk = is_array($record) ? ($record['disk'] ?? null) : ($record->disk ?? null);
                $path = is_array($record) ? ($record['path'] ?? null) : ($record->path ?? null);

                if (blank($disk) || blank($path)) {
                    return null;
                }

                /** @var FilesystemAdapter $adapter */
                $adapter = Storage::disk($disk);

                if (method_exists($adapter, 'temporaryUrl')) {
                    try {
                        return $adapter->temporaryUrl($path, now()->addMinutes(5));
                    } catch (\Throwable) {
                    }
                }

                try {
                    return $adapter->url($path);
                } catch (\Throwable) {
                    return route('core.file-explorer.open', ['disk' => $disk, 'path' => $path]);
                }
            })
            ->openRecordUrlInNewTab()
            ->headerActions([
                Action::make('root')
                    ->hiddenLabel()
                    ->color(Color::Neutral)
                    ->icon('heroicon-o-home')
                    ->visible(function (Component $livewire): bool {
                        /** @var CanNavigateThroughFileExplorer&Component $livewire */
                        return $livewire->isNavigating();
                    })
                    ->action(function (Component $livewire) {
                        /** @var CanNavigateThroughFileExplorer&Component $livewire */
                        $livewire->resetNavigation();
                    })
                    ->after(function (Component $livewire) {
                        /** @var CanNavigateThroughFileExplorer&Component $livewire */
                        if (method_exists($livewire, 'resetTable')) {
                            $livewire->resetTable();
                        }
                        $livewire->dispatch('$refresh');
                    }),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
            ])
            ->actions([
                Action::make('see')
                    ->hiddenLabel()
                    ->action(function (array $record, Component $livewire) {
                        /** @var CanNavigateThroughFileExplorer&Component $livewire */
                        $type = is_array($record) ? ($record['type'] ?? null) : ($record->type ?? null);
                        $name = is_array($record) ? ($record['name'] ?? null) : ($record->name ?? null);

                        switch ($type) {
                            case 'index':
                                if ($name) {
                                    $livewire->navigateToDisk($name);
                                }

                                break;
                            case 'parent':
                                $livewire->navigateToParentDirectory();

                                break;
                            case 'folder':
                                if (filled($name)) {
                                    $livewire->navigateToDirectory($name);
                                }

                                break;
                            default:
                                break;
                        }
                    })
                    ->after(function (Component $livewire) {
                        /** @var CanNavigateThroughFileExplorer&Component $livewire */
                        if (method_exists($livewire, 'resetTable')) {
                            $livewire->resetTable();
                        }
                        $livewire->dispatch('$refresh');
                    })
                    ->requiresConfirmation(false),
                Action::make('rename')
                    ->label('Renommer')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(function ($record): bool {
                        $type = is_array($record) ? ($record['type'] ?? null) : ($record->type ?? null);

                        return $type === 'file';
                    })
                    ->form([
                        TextInput::make('new_name')
                            ->label('Nouveau nom')
                            ->required()
                            ->rule('string')
                            ->maxLength(255)
                            ->suffix(function ($record) {
                                $name = is_array($record) ? ($record['name'] ?? '') : ($record->name ?? '');
                                $ext = pathinfo((string) $name, PATHINFO_EXTENSION);

                                return $ext !== '' ? '.' . $ext : '';
                            })
                            ->default(function ($record) {
                                $name = is_array($record) ? ($record['name'] ?? '') : ($record->name ?? '');
                                $base = pathinfo((string) $name, PATHINFO_FILENAME);

                                return $base;
                            }),
                    ])
                    ->action(function (array $data, $record, Component $livewire) {
                        $disk = is_array($record) ? ($record['disk'] ?? null) : ($record->disk ?? null);
                        $path = is_array($record) ? ($record['path'] ?? null) : ($record->path ?? null);
                        $name = is_array($record) ? ($record['name'] ?? null) : ($record->name ?? null);
                        $newName = trim($data['new_name'] ?? '');

                        if (blank($disk) || blank($path) || blank($name) || blank($newName)) {
                            Notification::make()->title('Paramètres manquants')->danger()->send();

                            return;
                        }

                        // Empêche les chemins
                        if (str_contains($newName, '/')) {
                            Notification::make()->title('Le nom ne doit pas contenir de "/"')->warning()->send();

                            return;
                        }

                        // Base d'origine (sans extension) et extension d'origine
                        $originalExt = pathinfo((string) $name, PATHINFO_EXTENSION);
                        $providedBase = pathinfo($newName, PATHINFO_FILENAME);

                        // Si l'utilisateur a fourni une extension, on l'ignore (on garde l'ext d'origine)
                        $finalBase = trim($providedBase);
                        if ($finalBase === '') {
                            Notification::make()->title('Le nom ne peut pas être vide')->warning()->send();

                            return;
                        }

                        $finalFileName = $originalExt !== '' ? ($finalBase . '.' . $originalExt) : $finalBase;

                        $directory = dirname($path);
                        $newPath = ($directory === '.' ? '' : $directory . '/') . $finalFileName;

                        if ($newPath === $path) {
                            Notification::make()->title('Aucune modification à appliquer')->info()->send();

                            return;
                        }

                        if (Storage::disk($disk)->exists($newPath)) {
                            Notification::make()->title('Un fichier avec ce nom existe déjà')->warning()->send();

                            return;
                        }

                        Storage::disk($disk)->move($path, $newPath);
                        Notification::make()->title('Fichier renommé')->success()->send();
                    })
                    ->after(function (Component $livewire) {
                        if (method_exists($livewire, 'resetTable')) {
                            $livewire->resetTable();
                        }
                        $livewire->dispatch('$refresh');
                    }),
                Action::make('delete')
                    ->label('Supprimer')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(function ($record): bool {
                        $type = is_array($record) ? ($record['type'] ?? null) : ($record->type ?? null);

                        return $type === 'file';
                    })
                    ->requiresConfirmation()
                    ->action(function ($record, Component $livewire) {
                        $disk = is_array($record) ? ($record['disk'] ?? null) : ($record->disk ?? null);
                        $path = is_array($record) ? ($record['path'] ?? null) : ($record->path ?? null);
                        if (blank($disk) || blank($path)) {
                            Notification::make()->title('Paramètres manquants')->danger()->send();

                            return;
                        }

                        Storage::disk($disk)->delete($path);
                        Notification::make()->title('Fichier supprimé')->success()->send();
                    })
                    ->after(function (Component $livewire) {
                        if (method_exists($livewire, 'resetTable')) {
                            $livewire->resetTable();
                        }
                        $livewire->dispatch('$refresh');
                    }),
            ]);
    }

    private static function getRecords(?string $disk = null, ?string $path = null, ?string $search = null): Collection
    {
        $instance = new FileExplorerService($disk);

        return collect(blank($disk) ? $instance->getIndex() : $instance->getFinder($path))
            ->when(filled($search), fn (Collection $data): Collection => $data->filter(
                fn (array $record): bool => str_contains(
                    strtolower($record['name']),
                    strtolower($search)
                ),
            ));
    }
}
