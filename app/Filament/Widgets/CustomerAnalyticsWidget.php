<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CustomerAnalyticsWidget extends ChartWidget
{
    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 3;

    public ?string $filter = '12months';

    public function getHeading(): ?string
    {
        return 'Customer Acquisition Trends';
    }

    public function getMaxHeight(): ?string
    {
        return '500px';
    }

    protected function getData(): array
    {
        $period = $this->filter;
        
        switch ($period) {
            case '6months':
                $months = 6;
                break;
            case '3months':
                $months = 3;
                break;
            default:
                $months = 12;
                break;
        }

        $data = collect(range(0, $months - 1))->map(function ($month) use ($months) {
            $date = now()->subMonths($months - 1 - $month);
            
            $newCustomers = Customer::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $newRentals = Rental::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            return [
                'month' => $date->format('M Y'),
                'customers' => $newCustomers,
                'rentals' => $newRentals,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Customers',
                    'data' => $data->pluck('customers')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'New Rentals',
                    'data' => $data->pluck('rentals')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            '3months' => 'Last 3 months',
            '6months' => 'Last 6 months',
            '12months' => 'Last 12 months',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}