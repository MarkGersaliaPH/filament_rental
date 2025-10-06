<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\Rental;
use Filament\Widgets\ChartWidget;

class OccupancyRateChartWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'md';

    public function getHeading(): ?string
    {
        return 'Occupancy Rate by Property Type';
    }

    public function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $propertyTypes = [];
        $occupancyRates = [];
        $backgroundColors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
            '#8B5CF6', '#F97316', '#06B6D4', '#84CC16'
        ];

        $colorIndex = 0;
        $colors = [];

        foreach (Property::TYPES as $type => $label) {
            $totalProperties = Property::where('type', $type)->count();
            
            if ($totalProperties > 0) {
                $occupiedProperties = Property::where('type', $type)
                    ->where('status', 'rented')
                    ->count();
                
                $occupancyRate = round(($occupiedProperties / $totalProperties) * 100, 1);
                
                $propertyTypes[] = $label;
                $occupancyRates[] = $occupancyRate;
                $colors[] = $backgroundColors[$colorIndex % count($backgroundColors)];
                $colorIndex++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Occupancy Rate (%)',
                    'data' => $occupancyRates,
                    'backgroundColor' => $colors,
                    'borderWidth' => 1,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $propertyTypes,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}