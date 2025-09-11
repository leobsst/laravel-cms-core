<?php

namespace Leobsst\LaravelCmsCore;

use Filament\Panel;
use Filament\Pages\Dashboard;
use Filament\Contracts\Plugin;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Leobsst\LaravelCmsCore\Filament\Auth\Login;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Leobsst\LaravelCmsCore\Services\FilamentService;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Leobsst\LaravelCmsCore\Filament\Auth\EditProfile;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Leobsst\LaravelCmsCore\Filament\Auth\RequestPasswordReset;
use Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\FileExplorer;

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
            ->login(Login::class)
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->recoverable()
                    ->regenerableRecoveryCodes(false),
                EmailAuthentication::make(),
            ])
            ->passwordReset(RequestPasswordReset::class)
            ->profile(EditProfile::class, false)
            ->colors([
                'primary' => FilamentService::getPrimaryColor(),
                'yellow' => Color::Amber,
            ])
            ->discoverResources(__DIR__.'/Filament/Resources', 'Leobsst\\LaravelCmsCore\\Filament\\Resources')
            ->discoverPages(__DIR__.'/Filament/Pages', 'Leobsst\\LaravelCmsCore\\Filament\\Pages')
            ->discoverWidgets(__DIR__.'/Filament/Widgets', 'Leobsst\\LaravelCmsCore\\Filament\\Widgets')
            ->pages([
                Dashboard::class,
                FileExplorer::class,
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware(middleware: [
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa()
            ->globalSearch()
            ->assets([
                Css::make('core', asset('css/filament/filament/core.css')),
            ])
            ->navigationItems($this->registerNavigationItems());
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
        return app(static::class);
    }

    private function registerNavigationItems(): array
    {
        return [
            NavigationItem::make('go_to_website')
                ->label('Voir mon site')
                ->url('/', true)
                ->icon('heroicon-o-globe-alt')
                ->sort(0),
        ];
    }
}
