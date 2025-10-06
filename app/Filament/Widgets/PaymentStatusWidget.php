<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatusWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    public function getHeading(): ?string
    {
        return 'Payment Status Overview';
    }

    protected function getStats(): array
    {
        // Calculate payment statistics
        $paidRentals = Rental::where('payment_status', 'paid')->count();
        $paidAmount = Rental::where('payment_status', 'paid')->sum('rent_amount');

        $pendingRentals = Rental::where('payment_status', 'pending')->count();
        $pendingAmount = Rental::where('payment_status', 'pending')->sum('rent_amount');

        $overdueRentals = Rental::where('payment_status', 'overdue')->count();
        $overdueAmount = Rental::where('payment_status', 'overdue')->sum('rent_amount');

        $totalRentals = $paidRentals + $pendingRentals + $overdueRentals;
        $collectonRate = $totalRentals > 0 ? round(($paidRentals / $totalRentals) * 100, 1) : 0;

        return [
            Stat::make('Paid', $paidRentals . ' rentals')
                ->description('$' . number_format($paidAmount, 2) . ' collected')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending', $pendingRentals . ' rentals')
                ->description('$' . number_format($pendingAmount, 2) . ' expected')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Overdue', $overdueRentals . ' rentals')
                ->description('$' . number_format($overdueAmount, 2) . ' overdue')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Collection Rate', $collectonRate . '%')
                ->description('Payment collection success')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($collectonRate >= 90 ? 'success' : ($collectonRate >= 75 ? 'warning' : 'danger')),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}