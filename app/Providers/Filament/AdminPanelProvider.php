<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\CustomerAnalyticsWidget;
use App\Filament\Widgets\OccupancyRateChartWidget;
use App\Filament\Widgets\PaymentStatusWidget;
use App\Filament\Widgets\PropertyStatusChartWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TopPropertiesWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Http\Middleware\AdminMiddleware;
use Filament\Auth\Pages\EditProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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
            ->login()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverviewWidget::class,
                RevenueChartWidget::class,
                PropertyStatusChartWidget::class,
                OccupancyRateChartWidget::class,
                PaymentStatusWidget::class,
                CustomerAnalyticsWidget::class,
                RecentActivitiesWidget::class,
                TopPropertiesWidget::class,
                QuickActionsWidget::class,
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                // AdminMiddleware::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(asset('images/logo.png'))
            // ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->brandLogoHeight('3rem')
            ->spa(hasPrefetching: true)
            ->unsavedChangesAlerts() 
        ->profile(isSimple: false)
        ->sidebarCollapsibleOnDesktop()
            // ->topbar(false)

            ->font('Roboto')
            // ->scale()

        ;
    }
}
