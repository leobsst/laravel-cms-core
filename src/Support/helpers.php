<?php

if (! function_exists('optimized')) {
    /**
     * Check if the asset has a optimized version (webp) and return it, otherwise return the original asset
     *
     * @param  string  $path  // path of the asset, example: 'pages/content/image.jpg'
     * @param  string  $disk  // 'public' or 'uploads
     */
    function optimized(string $path, string $disk = 'public'): string
    {
        $compatibleExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        $optimizedPath = str_replace(search: $compatibleExtensions, replace: 'webp', subject: $path);

        $destinationPath = $disk === 'uploads' ? 'uploads/'.$optimizedPath : 'storage/'.$optimizedPath;

        if (in_array(needle: pathinfo(path: $path, flags: PATHINFO_EXTENSION), haystack: $compatibleExtensions) &&
            file_exists(filename: public_path(path: $destinationPath))) {
            return asset(path: $destinationPath);
        }

        return $disk === 'uploads' ? asset(path: 'uploads/'.$path) : asset(path: 'storage/'.$path);
    }
}
