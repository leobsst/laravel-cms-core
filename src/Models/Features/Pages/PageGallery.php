<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryAlignment;
use Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class PageGallery
 *
 * @property int $id
 * @property int $page_id
 * @property string $identifier
 * @property PageGalleryOrientation $orientation
 * @property PageGalleryAlignment $alignment
 * @property ?string $name
 * @property ?string $description
 * @property Media[] $media
 * @property Page $page
 */
class PageGallery extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'page_id',
        'identifier',
        'orientation',
        'alignment',
        'name',
        'description',
    ];

    protected $casts = [
        'orientation' => PageGalleryOrientation::class,
        'alignment' => PageGalleryAlignment::class,
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('images')
            ->useDisk('page_galleries')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->quality(75)
            ->withResponsiveImages();
    }
}
