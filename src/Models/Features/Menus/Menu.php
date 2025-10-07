<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Menus;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @property string $name
 * @property MenuChild[] $children
 */
class Menu extends Model
{
    protected $fillable = [
        'name',
    ];

    public function children()
    {
        return $this->hasMany(MenuChild::class, 'menu_id')->whereNull('parent_id');
    }

    public static function getHeaderMenu()
    {
        return self::where('name', 'header')
            ->with('children')
            ->first()
            ->children()
            ->orderBy('order')
            ->get();
    }

    public static function getFooterMenuBetweenPos(int $start, int $end)
    {
        return self::where('name', 'footer')
            ->with('children')
            ->first()
            ->children()
            ->whereBetween('order', [$start, $end])
            ->orderBy('order')
            ->get();
    }
}
