<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CustomerAnalyticsWidget;
use App\Filament\Widgets\OccupancyRateChartWidget;
use App\Filament\Widgets\PaymentStatusWidget;
use App\Filament\Widgets\PropertyStatusChartWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TopPropertiesWidget;
use App\Filament\Widgets\QuickActionsWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Rentahan Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;

    protected static ?int $navigationSort = -2;

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 6,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            // PaymentStatusWidget::class,
            RevenueChartWidget::class,
            // [
            //     // PropertyStatusChartWidget::class,
            //     // OccupancyRateChartWidget::class,
            // ],
            CustomerAnalyticsWidget::class,
            // QuickActionsWidget::class,
            RecentActivitiesWidget::class,
            TopPropertiesWidget::class,
        ];
    }
}