<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

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
