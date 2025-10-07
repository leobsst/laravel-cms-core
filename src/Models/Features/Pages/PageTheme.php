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

    const FORBIDDEN_VALUES = [
        'home',
        'contact',
        'blog',
        'admin',
        'api',
        'dashboard',
        'login',
        'register',
        'password',
        'logout',
        'media',
        'css',
        'js',
        'fonts',
        'vendor',
        'storage',
        'build',
        'themes',
        'assets',
        'images',
        'img',
        'static',
        'public',
    ];

    protected $fillable = [
        'name',
        'banner',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'theme_id');
    }
}
