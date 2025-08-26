<?php

namespace Leobsst\LaravelCmsCore\Models\Features;

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
        return $this->hasMany(MenuChild::class, 'menu_id');
    }

    public static function getHeaderMenu()
    {
        return self::firstWhere('name', 'header')
            ->with('children')
            ->children()
            ->orderBy('order')
            ->get();
    }

    public static function getFooterMenuBetweenPos(int $start, int $end)
    {
        return self::firstWhere('name', 'footer')
            ->with('children')
            ->children()
            ->whereBetween('order', [$start, $end])
            ->orderBy('order')
            ->get();
    }
}
