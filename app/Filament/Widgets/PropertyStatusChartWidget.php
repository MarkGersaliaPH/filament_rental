<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Widgets\ChartWidget;

class PropertyStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'md';

    public function getHeading(): ?string
    {
        return 'Property Status Distribution';
    }

    public function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $statusData = Property::select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get status labels from Property model constants
        $statusLabels = [];
        $statusCounts = [];
        $backgroundColors = [
            'available' => '#10B981',
            'rented' => '#3B82F6', 
            'maintenance' => '#F59E0B',
            'inactive' => '#EF4444',
        ];

        $colors = [];

        foreach (Property::STATUSES as $status => $label) {
            $count = $statusData[$status] ?? 0;
            if ($count > 0) {
                $statusLabels[] = $label;
                $statusCounts[] = $count;
                $colors[] = $backgroundColors[$status] ?? '#6B7280';
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $statusCounts,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $statusLabels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}