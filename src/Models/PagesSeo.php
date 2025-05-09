<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Models\Page;
use Spatie\Tags\Tag;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property int $page_id
 * @property string $title
 * @property ?string $description
 * @property ?string $canonical
 * @property ?string $robots
 * @property ?string $og_title
 * @property ?string $og_description
 * @property ?string $og_image
 * @property ?string $og_url
 * @property ?string $og_type
 * @property ?string $og_locale
 * @property ?string $og_site_name
 * @property ?string $twitter_card
 * @property ?string $twitter_site
 * @property ?string $twitter_title
 * @property ?string $twitter_description
 * @property ?string $twitter_image
 * @property Page $page
 * @property Tag[] $tags
 */
class PagesSeo extends Model
{
    use HasTags;
    protected $table = 'pages_seo';
    protected $fillable = [
        'page_id',
        'title',
        'description',
        'canonical',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'og_url',
        'og_type',
        'og_locale',
        'og_site_name',
        'twitter_card',
        'twitter_site',
        'twitter_title',
        'twitter_description',
        'twitter_image',
    ];

    protected $casts = [
        'page_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'tags' => 'array',
        'canonical' => 'string',
        'robots' => 'string',
        'og_title' => 'string',
        'og_description' => 'string',
        'og_image' => 'string',
        'og_url' => 'string',
        'og_type' => 'string',
        'og_locale' => 'string',
        'og_site_name' => 'string',
        'twitter_card' => 'string',
        'twitter_site' => 'string',
        'twitter_title' => 'string',
        'twitter_description' => 'string',
        'twitter_image' => 'string',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get website tags
     *
     * @return string
     */
    public function getTagsFormattedAttribute(): string
    {
        return $this->tags()->pluck('name')->implode(', ');
    }
}
