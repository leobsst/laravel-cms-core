<?php

namespace Leobsst\LaravelCmsCore;

use Filament\Contracts\Plugin;
use Filament\Panel;

class LaravelCmsCorePlugin implements Plugin
{
    /**
     * The name of the plugin.
     *
     * @return string
     */
    public function getId(): string
    {
        return 'laravel-cms-core';
    }

    /**
     * Register discoverable resources, pages, and widgets.
     *
     * @param Panel $panel
     * @return void
     */
    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(in: __DIR__.'/Filament/Resources', for: 'Leobsst\\LaravelCmsCore\\Filament\\Resources')
            ->discoverPages(in: __DIR__.'/Filament/Pages', for: 'Leobsst\\LaravelCmsCore\\Filament\\Pages')
            ->discoverWidgets(in: __DIR__.'/Filament/Widgets', for: 'Leobsst\\LaravelCmsCore\\Filament\\Widgets');
    }

    /**
     * Boot the plugin.
     *
     * @param Panel $panel
     * @return void
     */
    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * Get the plugin.
     *
     * @return static
     */
    public static function make(): static
    {
        return app(abstract: static::class);
    }
}