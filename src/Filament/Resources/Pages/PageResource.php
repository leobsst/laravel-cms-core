<?php

namespace Leobsst\LaravelCmsCore\Filament\Resources\Pages;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Filament\Resources\Pages\Schemas\PagesForm;
use Leobsst\LaravelCmsCore\Filament\Resources\Pages\Tables\PagesTable;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Publications';

    protected static ?string $label = 'Mes pages';

    protected static ?string $navigationLabel = 'Pages';

    public static function form(Schema $schema): Schema
    {
        return PagesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'theme.name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'slug' => $record->slug,
            'theme' => $record->theme?->name,
            'courte description' => mb_strimwidth(strip_tags($record->content), 0, 100, '...'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('editor') && (\Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('pages'));
    }
}
