<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Models\MenuChild;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @property string $name
 * @property MenuChild[] $childrens
 */
class Menu extends Model
{
    protected $fillable = [
        'name'
    ];

    public function childrens()
    {
        return $this->hasMany(MenuChild::class, 'menu_id');
    }

    public static function getHeaderMenu()
    {
        return Menu::firstWhere('name', 'header')->childrens()->orderBy('order')->get();
    }

    public static function getFooterMenuBetweenPos(int $start, int $end)
    {
        return Menu::firstWhere('name', 'footer')->childrens()->whereBetween('order', [$start, $end])->orderBy('order')->get();
    }
}
