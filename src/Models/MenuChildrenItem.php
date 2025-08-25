<?php

namespace Leobsst\LaravelCmsCore\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuChild
 *
 * @property ?int $parent_id
 * @property string $name
 * @property ?string $url
 * @property ?int $page_id
 * @property int $order
 * @property ?string $icon
 * @property bool $is_active
 * @property MenuChild $parent
 * @property Page $page
 */
class MenuChildrenItem extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'page_id',
        'order',
        'icon',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(MenuChild::class, 'parent_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
