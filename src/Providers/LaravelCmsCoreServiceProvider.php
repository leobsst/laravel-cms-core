<?php

namespace Leobsst\LaravelCmsCore\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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
use Leobsst\LaravelCmsCore\Livewire\FlashMessage;
use Leobsst\LaravelCmsCore\Livewire\Page\Partials\Contact;
use Leobsst\LaravelCmsCore\Livewire\Page\Partials\Content;
use Leobsst\LaravelCmsCore\Models\Features\Menus\Menu;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;
use Leobsst\LaravelCmsCore\Models\Setting;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Livewire\Livewire;

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
        $this->getBladeDirectives();
        $this->registerLivewireComponents();

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $this->getSchedule($schedule);
        });

        // Pennant Feature Flag global scope
        Feature::resolveScopeUsing(fn ($drive) => null);
        EnsureFeaturesAreActive::whenInactive(fn (Request $request, array $features) => abort(404, 'Cette page est indisponible', [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]));

        // Passport
        Passport::enablePasswordGrant();

        require_once __DIR__.'/../Support/helpers.php';
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

    /**
     * Get the configs for the package.
     */
    private function getConfigs(): void
    {
        // Load config
        $this->mergeConfigFrom(__DIR__.'/../../config/core.php', 'core');
        $this->publishes([__DIR__.'/../../config/core.php' => config_path('core.php')], 'laravel-cms-core-config');
    }

    /**
     * Get the migrations for the package.
     */
    private function getMigrations(): void
    {
        // Load migrations
        $this->loadMigrationsFrom([__DIR__.'/../../database/migrations']);
        $this->publishesMigrations([__DIR__.'/../../database/migrations' => database_path('migrations')], 'laravel-cms-core-migrations');
    }

    /**
     * Get the seeders and factories for the package.
     */
    private function getSeedersAndFactories(): void
    {
        // Load factories and seeders
        $this->publishes([
            __DIR__.'/../../database/seeders' => database_path('seeders'),
            __DIR__.'/../../database/factories' => database_path('factories'),
            base_path('vendor/laravel/pennant/config/pennant.php') => config_path('pennant.php'),
        ], 'laravel-cms-core-database');
    }

    /**
     * Get the routes for the package.
     */
    private function getRoutes(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        $features = [];
        foreach (glob(__DIR__.'/../../routes/features/*.php') as $filename) {
            $this->loadRoutesFrom($filename);
            $features[$filename] = base_path('routes/features/'.basename($filename).'.php');
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->publishes(array_merge(
            [__DIR__.'/../../routes/web.php' => base_path('routes/web.php')],
            $features,
            [__DIR__.'/../../routes/api.php' => base_path('routes/api.php')],
        ), 'laravel-cms-core-routes');
    }

    private function getSchedule(Schedule $schedule): void
    {
        $schedule->command('queue:work --queue=emails --stop-when-empty --tries=3 --backoff=5')
            ->everySecond()
            ->withoutOverlapping();

        $schedule->command('queue:work --queue=high --stop-when-empty --tries=3 --backoff=5')
            ->everySecond()
            ->withoutOverlapping();

        $schedule->command('queue:work --queue=default --stop-when-empty --tries=3 --backoff=5')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('queue:flush --hours=48')
            ->daily()
            ->withoutOverlapping();

        // Converts all images to webp format from /public/assets/img folder
        $schedule->command('images:convert-to-webp')
            ->dailyAt(time: '03:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Terminates all logs older than 24 hours
        $schedule->command('logs:terminate')
            ->dailyAt(time: '06:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    /**
     * Get the views for the package.
     */
    private function getViews(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'laravel-cms-core');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/laravel-cms-core'),
        ], 'laravel-cms-core-views');

        // Publish assets
        $this->publishes([
            __DIR__.'/../../resources/assets' => asset('css/filament/filament'),
        ], 'laravel-cms-core-assets');
    }

    /**
     * Get the Blade directives for the package.
     */
    private function getBladeDirectives(): void
    {
        // Load Blade directives
        Blade::directive('isHome', function () {
            return "<?php if (session()->has('current_page') && \Leobsst\LaravelCmsCore\Models\Features\Pages\Page::find(session()->get('current_page'))->is_home) { ?>";
        });

        Blade::directive('endisHome', function () {
            return '<?php } ?>';
        });

        Blade::directive('isPage', function () {
            return "<?php if (session()->has('current_page') && !\Leobsst\LaravelCmsCore\Models\Features\Pages\Page::find(session()->get('current_page'))->is_home) { ?>";
        });

        Blade::directive('endisPage', function () {
            return '<?php } ?>';
        });
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
            $this->commands([
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

    /**
     * Register Livewire components
     */
    private function registerLivewireComponents(): void
    {
        Livewire::component('laravel-cms-core::page.partials.content', Content::class);
        Livewire::component('laravel-cms-core::page.partials.contact', Contact::class);
        Livewire::component('laravel-cms-core::flash-message', FlashMessage::class);
    }
}
