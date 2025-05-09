<?php

namespace Leobsst\LaravelCmsCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use Leobsst\LaravelCmsCore\Console\Commands\addChangeLog;
use Leobsst\LaravelCmsCore\Console\Commands\ConvertToWebp;
use Leobsst\LaravelCmsCore\Console\Commands\DeployCommand;
use Leobsst\LaravelCmsCore\Console\Commands\Faker\FakerLog;
use Leobsst\LaravelCmsCore\Console\Commands\TerminateLogs;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\AddTranslationToFile;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\NewTranslation;
use Leobsst\LaravelCmsCore\Console\Commands\Translation\Translate;

class LaravelCmsCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getMigrations();
        $this->getSeedersAndFactories();
        $this->getRoutes();
        $this->getViews();
        $this->getPackageInformations();
        $this->getCommands();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register any application services or bindings here
    }

    /**
     * Get the migrations for the package.
     *
     * @return void
     */
    private function getMigrations(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(paths: [__DIR__.'/../../database/migrations']);
        $this->publishesMigrations(paths: [__DIR__.'/../../database/migrations' => database_path(path: 'migrations')], groups: 'laravel-cms-core-migrations');
    }

    /**
     * Get the seeders and factories for the package.
     *
     * @return void
     */
    private function getSeedersAndFactories(): void
    {
        // Load factories and seeders
        $this->publishes(paths: [
            __DIR__.'/../../database/seeders' => database_path(path: 'seeders'),
            __DIR__.'/../../database/factories' => database_path(path: 'factories'),
        ], groups: 'laravel-cms-core-database');
    }

    /**
     * Get the routes for the package.
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
     */
    private function getPackageInformations(): void
    {
        // Load Informations
        AboutCommand::add('Laravel CMS Core', fn (): array => ['Author' => 'LEOBSST']);
    }

    /**
     * Get the commands for the package.
     *
     * @return void
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