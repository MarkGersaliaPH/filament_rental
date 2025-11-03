<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Property;
use App\Models\Rental;
use Filament\Actions\Action as ActionsAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Recent Activities';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rental::query()
                    ->with(['property', 'customer'])
                    ->latest()
                    ->limit(10)
            )
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            ->defaultSort('created_at', 'desc');
    }

    protected function getTableQuery(): Builder
    {
        return Rental::query()
            ->with(['property', 'customer'])
            ->latest()
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label('Date')
                ->dateTime('M d, Y')
                ->sortable(),

            TextColumn::make('customer.full_name')
                ->label('Customer')
                ->searchable()
                ->limit(20),

            TextColumn::make('property.title')
                ->label('Property')
                ->searchable()
                ->limit(30),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'pending' => 'warning',
                    'terminated' => 'danger',
                    'expired' => 'gray',
                    default => 'gray',
                }),

            TextColumn::make('rent_amount')
                ->label('Rent')
                ->money('USD')
                ->sortable(),

            // TextColumn::make('payment_status')
            //     ->label('Payment')
            //     ->badge()
            //     ->color(fn (?string $state): string => match ($state) {
            //         'paid' => 'success',
            //         'pending' => 'warning',
            //         'overdue' => 'danger',
            //         default => 'gray',
            //     }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionsAction::make('view')
                ->url(fn (Rental $record): string => "/admin/rentals/{$record->id}")
                ->icon('heroicon-m-eye')
                ->color('gray'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}