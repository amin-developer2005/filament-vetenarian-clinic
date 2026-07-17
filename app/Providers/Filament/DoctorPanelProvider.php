<?php

namespace App\Providers\Filament;

use App\Filament\Doctor\Pages\SignIn;
use App\Filament\Doctor\Widgets\WelcomeWidget;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Clinic;
use App\Support\PanelBrand;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('doctor')
            ->path('doctor')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(SignIn::class)
            ->tenant(Clinic::class)
            ->sidebarWidth('18rem')
            ->sidebarFullyCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Doctor/Resources'), for: 'App\Filament\Doctor\Resources')
            ->discoverPages(in: app_path('Filament/Doctor/Pages'), for: 'App\Filament\Doctor\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->brandName(fn() => PanelBrand::fetchPanelHeader())
            ->discoverWidgets(in: app_path('Filament/Doctor/Widgets'), for: 'App\Filament\Doctor\Widgets')
            ->widgets([
                WelcomeWidget::class,
            ])
            ->middleware([
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
                RoleMiddleware::class,
            ]);
    }
}
