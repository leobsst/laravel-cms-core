<?php

if (! function_exists('optimized')) {
    /**
     * Check if the asset has a optimized version (webp) and return it, otherwise return the original asset
     *
     * @param  string  $path  path of the asset, example: 'pages/content/image.jpg'
     * @param  string  $disk  'public' or 'uploads
     */
    function optimized(string $path, string $disk = 'public'): string
    {
        $compatibleExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        $optimizedPath = str_replace(search: $compatibleExtensions, replace: 'webp', subject: $path);

        $destinationPath = $disk === 'uploads' ? 'uploads/' . $optimizedPath : 'storage/' . $optimizedPath;

        if (in_array(needle: pathinfo(path: $path, flags: PATHINFO_EXTENSION), haystack: $compatibleExtensions) &&
            file_exists(filename: public_path(path: $destinationPath))) {
            return asset(path: $destinationPath);
        }

        return $disk === 'uploads' ? asset(path: 'uploads/' . $path) : asset(path: 'storage/' . $path);
    }
}

if (! function_exists('page_option_exists')) {
    /**
     * Check if the page has a specific option by its key
     *
     * @param  string | null  $slug  The page slug
     * @param  string  $option  The option key to check
     */
    function page_option_exists(?string $slug, string $option): bool
    {
        $page = \Leobsst\LaravelCmsCore\Models\Features\Pages\Page::where('slug', $slug)->with('options')->first(['id']);
        if (! $page) {
            return false;
        }

        return $page->options()->where('key', $option)->exists();
    }
}

if (! function_exists('page_option')) {
    /**
     * Get the value of a page option by its key
     *
     * @param  string | null  $slug  The page slug
     * @param  string  $option  The option key to retrieve
     * @param  mixed  $default  The default value to return if the option does not exist
     */
    function page_option(?string $slug, string $option, mixed $default = null): mixed
    {
        $page = \Leobsst\LaravelCmsCore\Models\Features\Pages\Page::where('slug', $slug)->with('options')->first(['id']);
        if (! $page) {
            return $default;
        }

        $optionRecord = $page->options()->where('key', $option)->first(['value']);

        return $optionRecord !== null && filled($optionRecord->value) ? $optionRecord->value : $default;
    }
}
