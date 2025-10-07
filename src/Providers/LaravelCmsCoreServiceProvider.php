<?php

namespace Leobsst\LaravelCmsCore\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCmsCoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cms-core')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->publishAssets()
                    ->askToStarRepoOnGitHub('leobsst/laravel-cms-core');
            })
            ->hasConfigFile()
            ->discoversMigrations()
            ->hasCommands([
                addChangeLog::class,
                ConvertToWebp::class,
                DeployCommand::class,
                TerminateLogs::class,
                AddTranslationToFile::class,
                NewTranslation::class,
                Translate::class,
                FakerLog::class,
            ])
            ->hasAssets()
            ->hasRoutes($this->getRoutes());

        $this->getSeedersAndFactories();
        $this->getViews();
        $this->getBladeDirectives();

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

        require_once __DIR__ . '/../Support/helpers.php';
    }

    public function packageBooted(): void
    {
        $this->registerLivewireComponents();

        // Register Blade components
        FilamentAsset::register([
            Js::make('core', __DIR__ . '/../../resources/dist/core.js'),
        ], 'leobsst/laravel-cms-core');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function registeringPackage()
    {
        session()->put('cookie_consent');
        $loader = AliasLoader::getInstance();

        // Add your aliases
        $loader->alias('Setting', Setting::class);
        $loader->alias('ClientService', ClientService::class);
        $loader->alias('Menu', Menu::class);
        $loader->alias('Page', Page::class);
    }

    public function packageRegistered(): void {}

    /**
     * Get the seeders and factories for the package.
     */
    private function getSeedersAndFactories(): void
    {
        // Load factories and seeders
        $this->publishes([
            __DIR__ . '/../../database/seeders' => database_path('seeders'),
            __DIR__ . '/../../database/factories' => database_path('factories'),
            base_path('vendor/laravel/pennant/config/pennant.php') => config_path('pennant.php'),
        ], 'laravel-cms-core-database');
    }

    /**
     * Get the routes for the package.
     */
    private function getRoutes(): array
    {
        $features = [];
        foreach (glob(__DIR__ . '/../../routes/features/*.php') as $filename) {
            $features[] = 'features/' . pathinfo($filename, PATHINFO_FILENAME);
        }

        return array_merge(['web'], $features, ['api']);
    }

    private function getSchedule(Schedule $schedule): void
    {
        // Better to run the queue worker with a process manager like Supervisor
        // $schedule->command('queue:work --queue=emails --stop-when-empty --tries=3 --backoff=5')
        //     ->everySecond()
        //     ->withoutOverlapping();

        // $schedule->command('queue:work --queue=high --stop-when-empty --tries=3 --backoff=5')
        //     ->everySecond()
        //     ->withoutOverlapping();

        // $schedule->command('queue:work --queue=default --stop-when-empty --tries=3 --backoff=5')
        //     ->everyMinute()
        //     ->withoutOverlapping();

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

    private function getViews(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laravel-cms-core');
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/laravel-cms-core'),
        ], 'laravel-cms-core-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../resources/css' => asset('css/filament/filament'),
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
            return "<?php if (session()->has('current_page') && ! \Leobsst\LaravelCmsCore\Models\Features\Pages\Page::find(session()->get('current_page'))->is_home) { ?>";
        });

        Blade::directive('endisPage', function () {
            return '<?php } ?>';
        });

        Blade::directive('pageOptionExists', function (string $expression) {
            return "<?php if (page_option_exists({$expression})) { ?>";
        });

        Blade::directive('endpageOptionExists', function () {
            return '<?php } ?>';
        });

        Blade::directive('pageOption', function (string $expression) {
            return "<?php echo page_option({$expression}); ?>";
        });
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
