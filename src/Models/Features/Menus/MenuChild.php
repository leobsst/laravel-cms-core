<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

/**
 * Class MenuChild
 *
 * @property string $name
 * @property ?string $url
 * @property ?int $page_id
 * @property int $menu_id
 * @property int $order
 * @property ?string $icon
 * @property bool $is_active
 * @property bool $is_default
 * @property Menu $menu
 * @property MenuChild[] $children
 * @property Page $page
 */
class MenuChild extends Model
{
    protected $fillable = [
        'name',
        'url',
        'page_id',
        'menu_id',
        'parent_id',
        'order',
        'icon',
        'is_active',
        'is_default',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
