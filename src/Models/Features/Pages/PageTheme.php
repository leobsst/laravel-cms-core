<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PageTheme
 *
 * @property int $id
 * @property string $name
 * @property ?string $banner
 * @property Collection|Page[] $pages
 */
class PageTheme extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'banner',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'theme_id');
    }
}
