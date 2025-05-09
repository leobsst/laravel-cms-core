<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Models\Menu;
use Leobsst\LaravelCmsCore\Models\Page;
use Leobsst\LaravelCmsCore\Models\MenuChildrenItem;
use Illuminate\Database\Eloquent\Model;

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
 * @property MenuChildrenItem[] $childrens
 * @property Page $page
 */
class MenuChild extends Model
{
    protected $fillable = [
        'name',
        'url',
        'page_id',
        'menu_id',
        'order',
        'icon',
        'is_active',
        'is_default',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function childrens()
    {
        return $this->hasMany(MenuChildrenItem::class, 'parent_id')->orderBy('order');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
