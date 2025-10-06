<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Property;
use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total properties
        $totalProperties = Property::count();
        
        // Calculate active rentals
        $activeRentals = Rental::where('status', 'active')->count();
        
        // Calculate occupancy rate
        $occupancyRate = $totalProperties > 0 ? round(($activeRentals / $totalProperties) * 100, 1) : 0;
        
        // Calculate monthly revenue
        $monthlyRevenue = Rental::where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('rent_amount');
        
        // Calculate revenue growth compared to last month
        $lastMonthRevenue = Rental::where('status', 'active')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('rent_amount');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        // // Calculate pending applications
        // $pendingApplications = Rental::where('status', 'pending')->count();
        
        // Calculate overdue payments
        $overduePayments = Rental::where('payment_status', 'overdue')->count();

        return [
            Stat::make('Total Properties', $totalProperties)
                ->description("$activeRentals active Rentals")
                ->descriptionIcon('heroicon-m-home')
                ->chart([7, 12, 8, 15, 18, 22, $totalProperties])
                ->color('primary'),
                
            // Stat::make('Active Rentals', $activeRentals)
            //     ->description($occupancyRate . '% occupancy rate')
            //     ->descriptionIcon('heroicon-m-users')
            //     ->chart([3, 8, 12, 15, 18, 22, $activeRentals])
            //     ->color('primary'),
                
            Stat::make('Monthly Revenue', '$' . number_format($monthlyRevenue, 2))
                ->description(($revenueGrowth >= 0 ? '+' : '') . $revenueGrowth . '% from last month')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([1200, 1800, 2200, 2800, 3200, 3600, $monthlyRevenue])
                ->color($revenueGrowth >= 0 ? 'primary' : 'danger'),
                
            // Stat::make('Pending Applications', $pendingApplications)
            //     ->description('Awaiting approval')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('warning'),
                
            Stat::make('Overdue Payments', $overduePayments)
                ->description('Require attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overduePayments > 0 ? 'danger' : 'primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}