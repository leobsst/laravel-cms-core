<div>
    <div @class([
        'w-full grid grid-cols-1 gap-4',
        'md:grid-cols-2' => $gallery->orientation === \Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation::HORIZONTAL && $media->count() <= 2,
        'md:grid-cols-4' => $gallery->orientation === \Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation::HORIZONTAL && $media->count() > 3 && $media->count() % 2 === 0,
        'md:grid-cols-3' => $gallery->orientation === \Leobsst\LaravelCmsCore\Enums\Features\Pages\PageGalleryOrientation::HORIZONTAL && $media->count() > 2 && $media->count() % 2 !== 0,
    ])>
@foreach ($media as $image)
        <img src="{{ $image->getUrl() }}" alt="" class="w-full">
@endforeach
    </div>
</div>