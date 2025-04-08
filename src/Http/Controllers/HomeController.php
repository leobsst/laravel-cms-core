<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

use App\Models\Page;

class HomeController extends CoreController
{
    /**
     * generation de la sitemap
     */
    public function getSiteMap()
    {
        $routes = [];

        return response()->view('laravel-cms-core::sitemap', [
            'routes' => array_merge(['/'], $routes, ['contact']),
        ])->header('Content-Type', 'text/xml');
    }
}
