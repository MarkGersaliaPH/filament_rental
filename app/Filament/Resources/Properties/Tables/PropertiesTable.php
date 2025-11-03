<?php

namespace App\Filament\Resources\Properties\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Models\Property;
use Filament\Actions\ViewAction;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Main property image - circular and first column
                ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl('https://via.placeholder.com/150x150/e5e7eb/6b7280?text=No+Image')
                    ->getStateUsing(function ($record) {
                        $images = $record->images;
                        return is_array($images) && count($images) > 0 ? $images[0] : $images;
                    }),
                    
                // Essential property information
                TextColumn::make('title')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->description(fn ($record) => $record->address . ', ' . $record->city),
                    
                // Essential property information
                TextColumn::make('landlord.full_name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                    
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'apartment' => 'info',
                        'house' => 'success',
                        'studio' => 'warning',
                        'villa' => 'danger',
                        'penthouse' => 'purple',
                        default => 'gray',
                    }),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'rented' => 'info',
                        'maintenance' => 'warning',
                        'inactive' => 'gray',
                        default => 'gray',
                    }),
                    
                TextColumn::make('bedrooms')
                    ->label('Bed/Bath')
                    ->formatStateUsing(fn ($record) => $record->bedrooms . '/' . $record->bathrooms)
                    ->alignCenter(),
                    
                TextColumn::make('rent_amount')
                    ->label('Rent')
                    ->money('USD')
                    ->sortable()
                    ->description(fn ($record) => '/' . $record->rent_period)
                    ->color('success')
                    ->weight('bold'),
                    
                TextColumn::make('city')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->neighborhood ?? $record->state_province),
                    
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                    
                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->color('info'),
                    
                TextColumn::make('created_at')
                    ->label('Listed')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                // Additional details in toggleable columns
                TextColumn::make('area_sqft')
                    ->label('Area (sqft)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                IconColumn::make('furnished')
                    ->label('Furnished')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                IconColumn::make('pet_friendly')
                    ->label('Pet Friendly')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('available_from')
                    ->label('Available From')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Property::STATUSES)
                    ->multiple(),
                    
                SelectFilter::make('type')
                    ->options(Property::TYPES)
                    ->multiple(),
                    
                SelectFilter::make('city')
                    ->options(fn () => Property::distinct('city')
                        ->orderBy('city')
                        ->pluck('city', 'city')
                        ->toArray()
                    )
                    ->searchable(),
                    
                SelectFilter::make('bedrooms')
                    ->options([
                        '0' => '0 (Studio)',
                        '1' => '1 Bedroom',
                        '2' => '2 Bedrooms',
                        '3' => '3 Bedrooms',
                        '4' => '4+ Bedrooms',
                    ]),
                    
                SelectFilter::make('is_featured')
                    ->label('Featured')
                    ->options([
                        '1' => 'Featured Only',
                        '0' => 'Non-Featured Only',
                    ]),
                    
                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-home')
            ->emptyStateHeading('No properties yet')
            ->emptyStateDescription('Once you add a property, it will appear here.');
    }
}
