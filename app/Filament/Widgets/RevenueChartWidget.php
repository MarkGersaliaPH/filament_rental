<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 3;

    public ?string $filter = '12months';

    public function getHeading(): ?string
    {
        return 'Monthly Revenue';
    }

    public function getMaxHeight(): ?string
    {
        return '151px';
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
            
            $revenue = Rental::where('status', 'active')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('rent_amount');

            return [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('revenue')->toArray(),
                    // 'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    // 'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}