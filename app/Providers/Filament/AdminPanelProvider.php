<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\SignIn;
use App\Filament\Pages\Tenancy\EditClinicProfile;
use App\Filament\Pages\Tenancy\RegisterClinic;
use App\Filament\Widgets\AdminDashboardStats;
use App\Filament\Widgets\AppointmentTrendChart;
use App\Filament\Widgets\UserAccountWidget;
use App\Filament\Widgets\WelcomeWidget;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->tenant(Clinic::class)
            ->login(SignIn::class)
            ->sidebarWidth('18rem')
            ->collapsedSidebarWidth('12rem')
            ->spa()
            ->brandName(fn() => PanelBrand::fetchPanelHeader())
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                WelcomeWidget::class,
                AppointmentTrendChart::class,
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
