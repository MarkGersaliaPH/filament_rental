<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\Rental;
use Filament\Actions\Action as ActionsAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPropertiesWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Top Performing Properties';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Property::query()
                    ->withCount(['rentals as total_rentals'])
                    ->withSum(['rentals as total_revenue'], 'rent_amount')
                    ->having('total_rentals', '>', 0)
                    ->orderByDesc('total_revenue')
                    ->limit(8)
            )
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            ->defaultSort('total_revenue', 'desc');
    }

    protected function getTableQuery(): Builder
    {
        return Property::query()
            ->withCount(['rentals as total_rentals'])
            ->withSum(['rentals as total_revenue'], 'rent_amount')
            ->having('total_rentals', '>', 0)
            ->orderByDesc('total_revenue')
            ->limit(8);
    }

    protected function getTableColumns(): array
    {
        return [
                  ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl('https://via.placeholder.com/150x150/e5e7eb/6b7280?text=No+Image')
                    ->getStateUsing(function ($record) {
                        $images = $record->images;
                        return is_array($images) && count($images) > 0 ? $images[0] : $images;
                    }),
            TextColumn::make('title')
                ->label('Property')
                ->searchable()
                ->limit(30)
                ->weight('bold'),

            TextColumn::make('type')
                ->label('Type')
                ->badge()
                ->color('gray'),

            TextColumn::make('city')
                ->label('Location')
                ->searchable(),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'available' => 'success',
                    'rented' => 'primary',
                    'maintenance' => 'warning',
                    'inactive' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('total_rentals')
                ->label('Total Rentals')
                ->alignCenter()
                ->badge()
                ->color('primary'),

            TextColumn::make('total_revenue')
                ->label('Total Revenue')
                ->money('USD')
                ->weight('bold')
                ->color('success'),

            TextColumn::make('rent_amount')
                ->label('Current Rent')
                ->money('USD')
                ->sortable(),

            TextColumn::make('view_count')
                ->label('Views')
                ->alignCenter()
                ->default(0),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionsAction::make('view')
                ->url(fn (Property $record): string => "/admin/properties/{$record->id}")
                ->icon('heroicon-m-eye')
                ->color('gray'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'total_revenue';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}