<?php

namespace Leobsst\LaravelCmsCore;

use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Contracts\Plugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leobsst\LaravelCmsCore\Filament\Auth\EditProfile;
use Leobsst\LaravelCmsCore\Filament\Auth\Login;
use Leobsst\LaravelCmsCore\Filament\Auth\RequestPasswordReset;
use Leobsst\LaravelCmsCore\Filament\Pages\FileExplorer\FileExplorer;
use Leobsst\LaravelCmsCore\Services\FilamentService;

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
            ->discoverResources(__DIR__ . '/Filament/Resources', 'Leobsst\\LaravelCmsCore\\Filament\\Resources')
            ->discoverPages(__DIR__ . '/Filament/Pages', 'Leobsst\\LaravelCmsCore\\Filament\\Pages')
            ->discoverWidgets(__DIR__ . '/Filament/Widgets', 'Leobsst\\LaravelCmsCore\\Filament\\Widgets')
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
            ->viteTheme('resources/css/filament/core/theme.css')
            ->navigationItems($this->registerNavigationItems())
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('laravel-cms-core::filament.hooks.fix-sidebar-position')
            );
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
