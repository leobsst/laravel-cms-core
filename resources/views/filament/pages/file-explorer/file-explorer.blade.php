<x-filament-panels::page>
    <div class="flex items-center justify-between mb-4">
        <div class="text-sm text-gray-500">
            {{ $this->getCurrentPath() }}
        </div>
        <div class="space-x-2">
            <x-filament::button size="sm" color="gray" wire:click="setListView" :disabled="$this->viewMode === 'list'">
                Liste
            </x-filament::button>
            <x-filament::button size="sm" color="gray" wire:click="setGridView" :disabled="$this->viewMode === 'grid'">
                Grille
            </x-filament::button>
        </div>
    </div>

    @if ($this->viewMode === 'list')
        {{ $this->table }}
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($this->getGridRecords() as $record)
                @php
                    $isFile = ($record['type'] ?? null) === 'file';
                    $isFolder = ($record['type'] ?? null) === 'folder';
                    $isIndex = ($record['type'] ?? null) === 'index';
                    $isParent = ($record['type'] ?? null) === 'parent';
                    $disk = $record['disk'] ?? null;
                    $path = $record['path'] ?? null;
                    $name = $record['name'] ?? null;
                    $url = null;
                    if ($isFile && $disk && $path) {
                        try {
                            /** @var \Illuminate\\Filesystem\\FilesystemAdapter $adapter */
                            $adapter = Storage::disk($disk);
                            if (method_exists($adapter, 'temporaryUrl')) {
                                $url = $adapter->temporaryUrl($path, now()->addMinutes(5));
                            } else {
                                $url = $adapter->url($path);
                            }
                        } catch (\Throwable $e) {
                            $url = route('core.file-explorer.open', ['disk' => $disk, 'path' => $path]);
                        }
                    }
                @endphp
                <div class="group border rounded-lg p-3 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between mb-2">
                        @if($isFile && $url)
                            <a href="{{ $url }}" target="_blank" class="text-xs text-primary-600 hover:underline">Ouvrir</a>
                        @endif
                    </div>
                    <button class="text-left w-full" @click.prevent="
                        @this.{{
                            $isIndex ? 'navigateToDisk' : (
                                $isParent ? 'navigateToParentDirectory' : (
                                    $isFolder ? 'navigateToDirectory' : null
                                )
                            )
                        }}({{ $isIndex ? ('\''.$name.'\'') : ($isFolder ? ('\''.$name.'\'') : '') }})
                    ">
                        <div class="font-medium truncate">{{ $name }}</div>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>