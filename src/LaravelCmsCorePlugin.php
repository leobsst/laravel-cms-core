<?php

namespace Leobsst\LaravelCmsCore;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;

class LaravelCmsCorePlugin implements Plugin
{
    /**
     * The name of the plugin.
     */
    public function getId(): string
    {
        return 'laravel-cms-core';
    }

    /**
     * Register discoverable resources, pages, and widgets.
     */
    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(in: __DIR__.'/Filament/Resources', for: 'Leobsst\\LaravelCmsCore\\Filament\\Resources')
            ->discoverPages(in: __DIR__.'/Filament/Pages', for: 'Leobsst\\LaravelCmsCore\\Filament\\Pages')
            ->discoverWidgets(in: __DIR__.'/Filament/Widgets', for: 'Leobsst\\LaravelCmsCore\\Filament\\Widgets')
            ->assets([
                Css::make('core', asset('css/filament/filament/core.css')),
            ]);
    }

    /**
     * Boot the plugin.
     */
    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * Get the plugin.
     */
    public static function make(): static
    {
        return app(abstract: static::class);
    }
}
