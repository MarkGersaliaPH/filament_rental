<?php

namespace App\Filament\Resources\Properties\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SimplePropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('type')
                    ->badge(),
                    
                TextColumn::make('status')
                    ->badge(),
                    
                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('rent_amount')
                    ->money('USD')
                    ->sortable(),
                    
                TextColumn::make('bedrooms')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}