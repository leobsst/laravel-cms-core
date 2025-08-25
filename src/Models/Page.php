<?php

namespace Leobsst\LaravelCmsCore\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property int $id
 * @property string $title
 * @property ?string $title-content
 * @property ?string $slug
 * @property ?string $content
 * @property ?string $banner
 * @property bool $is_published
 * @property bool $is_home
 * @property bool $is_default
 * @property ?string $published_at
 * @property PagesSeo $seo
 * @property Collection|PageStat[] $stats
 * @property MenuChild $menu
 */
class Page extends Model
{
    protected $fillable = [
        'title',
        'title-content',
        'slug',
        'content',
        'banner',
        'is_published',
        'is_home',
        'is_default',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_home' => 'boolean',
        'is_default' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function seo()
    {
        return $this->hasOne(PagesSeo::class);
    }

    public function stats()
    {
        return $this->hasMany(PageStat::class, 'page_id');
    }

    public function menu()
    {
        return $this->hasOne(MenuChild::class, 'page_id');
    }

    public static function cleanContent(string $content): string
    {
        $content = preg_replace([
            '/\s*mso\s*([^;]+);/si',
            '/\s*MsoNormal/si',
            '/<!--\s*\[(.*?)\]-->/si',
            '/\s*color:\s*black;/si',
        ], '', $content);
        $content = preg_replace('/\s*font-family:\s*([^;]+);/si', '', $content);
        $content = preg_replace([
            '/\s*style="\s*"/si',
            '/\s*style=\'\s*\'/si',
            '/\s*class="\s*"/si',
            '/\s*class=\'\s*\'/si',
        ], '', $content);

        return $content;
    }

    public static function cleanSlug(string $slug): string
    {
        setlocale(LC_ALL, 'en_US.utf8');
        $slug = urlencode(str_replace(' ', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim(strtolower($slug)))));
        setlocale(LC_ALL, 'fr_FR.utf8');

        return $slug;
    }
}
