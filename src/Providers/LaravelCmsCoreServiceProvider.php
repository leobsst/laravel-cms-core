<?php

namespace Leobsst\LaravelCmsCore\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Leobsst\LaravelCmsCore\Console\Commands\addChangeLog;
use Leobsst\LaravelCmsCore\Console\Commands\ConvertToWebp;
use Leobsst\LaravelCmsCore\Console\Commands\DeployCommand;
use Leobsst\LaravelCmsCore\Console\Commands\Faker\FakerLog;
use Leobsst\LaravelCmsCore\Console\Commands\TerminateLogs;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\AddTranslationToFile;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\NewTranslation;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\Translate;
use Leobsst\LaravelCmsCore\Models\Features\Menus\Menu;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Services\ClientService;

class LaravelCmsCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getConfigs();
        $this->getMigrations();
        $this->getSeedersAndFactories();
        $this->getRoutes();
        $this->getViews();
        $this->getPackageInformations();
        $this->getCommands();

        // Pennant Feature Flag global scope
        Feature::resolveScopeUsing(fn ($drive) => null);
        EnsureFeaturesAreActive::whenInactive(callback: fn (Request $request, array $features) => abort(404, 'Cette page est indisponible', [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ])
        );

        // Passport
        Passport::enablePasswordGrant();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        session()->put('cookie_consent');
        $loader = AliasLoader::getInstance();

        // Add your aliases
        $loader->alias('Setting', Setting::class);
        $loader->alias('ClientService', ClientService::class);
        $loader->alias('Menu', Menu::class);
        $loader->alias('Page', Page::class);
    }

    private function getConfigs(): void
    {
        // Load config
        $this->mergeConfigFrom(__DIR__.'/../../config/core.php', 'core');
        $this->publishes(paths: [__DIR__.'/../../config/core.php' => config_path(path: 'core.php')], groups: 'laravel-cms-core-config');
    }

    /**
     * Get the migrations for the package.
     */
    private function getMigrations(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(paths: [__DIR__.'/../../database/migrations']);
        $this->publishesMigrations(paths: [__DIR__.'/../../database/migrations' => database_path(path: 'migrations')], groups: 'laravel-cms-core-migrations');
    }

    /**
     * Get the seeders and factories for the package.
     */
    private function getSeedersAndFactories(): void
    {
        // Load factories and seeders
        $this->publishes(paths: [
            __DIR__.'/../../database/seeders' => database_path(path: 'seeders'),
            __DIR__.'/../../database/factories' => database_path(path: 'factories'),
            base_path('vendor/laravel/pennant/config/pennant.php') => config_path(path: 'pennant.php'),
        ], groups: 'laravel-cms-core-database');
    }

    /**
     * Get the routes for the package.
     */
    private function getRoutes(): void
    {
        // Load routes
        $this->loadRoutesFrom(path: __DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(path: __DIR__.'/../../routes/api.php');
        $this->loadRoutesFrom(path: __DIR__.'/../../routes/console.php');
        $this->publishes([
            __DIR__.'/../../routes/web.php' => base_path(path: 'routes/web.php'),
            __DIR__.'/../../routes/api.php' => base_path(path: 'routes/api.php'),
            __DIR__.'/../../routes/console.php' => base_path(path: 'routes/console.php'),
        ], groups: 'laravel-cms-core-routes');
    }

    /**
     * Get the views for the package.
     */
    private function getViews(): void
    {
        // Load views
        $this->loadViewsFrom(path: __DIR__.'/../../resources/views', namespace: 'laravel-cms-core');
        $this->publishes(paths: [
            __DIR__.'/../../resources/views' => resource_path(path: 'views/vendor/courier'),
        ], groups: 'laravel-cms-core-views');
    }

    /**
     * Get the package information for the package.
     */
    private function getPackageInformations(): void
    {
        // Load Informations
        AboutCommand::add('Laravel CMS Core', fn (): array => [
            'Authors' => [
                'LEOBSST',
                'B.L.A.M. PRODUCTION',
            ],
        ]);
    }

    /**
     * Get the commands for the package.
     */
    private function getCommands(): void
    {
        // Load commands
        if ($this->app->runningInConsole()) {
            $this->commands(commands: [
                addChangeLog::class,
                ConvertToWebp::class,
                DeployCommand::class,
                TerminateLogs::class,
                AddTranslationToFile::class,
                NewTranslation::class,
                Translate::class,
                FakerLog::class,
            ]);
        }
    }
}
