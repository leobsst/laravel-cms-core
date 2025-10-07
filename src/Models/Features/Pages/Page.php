<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Leobsst\LaravelCmsCore\Models\Features\Menus\MenuChild;

/**
 * Class Page
 *
 * @property int $id
 * @property string $title
 * @property ?string $title_content
 * @property ?string $slug
 * @property ?int $theme_id
 * @property bool $no_content
 * @property ?string $content
 * @property ?string $draft
 * @property ?string $banner
 * @property bool $is_published
 * @property bool $is_home
 * @property bool $is_default
 * @property bool $no_footer
 * @property ?string $published_at
 * @property string $full_path
 * @property \Illuminate\Database\Eloquent\Collection<int, PageOption> $options
 * @property PagesSeo $seo
 * @property Collection|PageStat[] $stats
 * @property MenuChild $menu
 * @property ?PageTheme $theme
 * @property Collection|PageGallery[] $galleries
 */
class Page extends Model
{
    protected $fillable = [
        'title',
        'title_content',
        'slug',
        'theme_id',
        'no_content',
        'content',
        'draft',
        'banner',
        'is_published',
        'is_home',
        'is_default',
        'no_footer',
        'published_at',
    ];

    protected $casts = [
        'no_content' => 'boolean',
        'is_published' => 'boolean',
        'is_home' => 'boolean',
        'is_default' => 'boolean',
        'no_footer' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(PageOption::class, 'page_id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(PagesSeo::class);
    }

    public function stats(): HasMany
    {
        return $this->hasMany(PageStat::class, 'page_id');
    }

    public function menu(): HasOne
    {
        return $this->hasOne(MenuChild::class, 'page_id');
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(PageTheme::class, 'theme_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(PageGallery::class, 'page_id');
    }

    public function getFullPathAttribute(): string
    {
        if ($this->slug) {
            return $this->theme ? $this->theme->name . '/' . $this->slug : $this->slug;
        }

        return '/';
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
        $slug = str_replace(' ', '-', $slug);

        return Str::slug($slug);
    }
}
