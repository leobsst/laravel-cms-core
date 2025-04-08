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
        // Load migrations
        $this->publishesMigrations(paths: [__DIR__.'/../database/migrations' => database_path(path: 'migrations')], groups: 'laravel-cms-core-migrations');

        // Load views
        $this->loadViewsFrom(path: __DIR__.'/../resources/views', namespace: 'laravel-cms-core');
        $this->publishes(paths: [
            __DIR__.'/../resources/views' => resource_path(path: 'views/vendor/courier'),
        ]);

        // Load Informations
        AboutCommand::add('Laravel CMS Core', fn (): array => ['Version' => '0.1.4', 'Author' => 'LEOBSST']);

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

        if ($this->app->runningInConsole()) {
            $this->optimizes(
                optimize: 'laravel-cms-core:optimize',
                clear: 'laravel-cms-core:clear-optimizations',
            );
        }
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
}