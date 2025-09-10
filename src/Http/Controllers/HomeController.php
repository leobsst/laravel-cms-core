<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

class HomeController extends CoreController
{
    /**
     * generation de la sitemap
     */
    public function getSiteMap()
    {
        $routes = ['contact'];

        $routes = array_merge($this->getPagesSlugs(), $routes);

        return response()->view('laravel-cms-core::sitemap', [
            'routes' => array_merge(['/'], $routes),
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * Récupère les slugs des pages publiées, à l'exception de la page d'accueil
     */
    private function getPagesSlugs(): array
    {
        $pages = [];

        if (\Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('pages')) {
            $pages = Page::where('is_published', true)
                ->where('is_home', false)
                ->with('theme:id,name')
                ->get(['slug', 'theme_id'])
                ->pluck('full_path')
                ->toArray();
        }

        return $pages;
    }
}
