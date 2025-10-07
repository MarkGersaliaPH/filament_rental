<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Rental;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Billing Information')
                    ->schema([
                        MorphToSelect::make('billable')
                            ->types([
                                MorphToSelect\Type::make(Rental::class)
                                    ->titleAttribute('id'),
                            ])->columnSpanFull(),
                        Select::make('bill_to_id')
                            ->label('Bill To')
                            ->relationship('billTo', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                        Select::make('bill_from_id')
                            ->label('Bill From')
                            ->relationship('billFrom', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                    
                Section::make('Invoice Details')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->default(function () {
                                $latestInvoice = \App\Models\Invoice::latest('id')->first();
                                $nextNumber = $latestInvoice ? $latestInvoice->id + 1 : 1;

                                return 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                            })
                            ->required()
                            ->columnSpan(1),
                        DatePicker::make('invoice_date')
                            ->required()
                            ->columnSpan(1),
                        DatePicker::make('due_date')
                            ->columnSpan(1),
                        Select::make('status')
                            ->options(['pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled'])
                            ->default('pending')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                    
                Section::make('Invoice Items')
                    ->schema([
                        Repeater::make('invoice_items')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Item Name')
                                    ->required()
                                    ->placeholder('e.g., Rent')
                                    ->columnSpan(2)
                                    ->live(),
                                TextInput::make('price_per_unit')
                                    ->label('Price Per Unit')
                                    ->required()
                                    ->numeric()
                                    ->step(0.01)
                                    ->prefix('₱')
                                    ->columnSpan(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $quantity = $get('quantity') ?: 1;
                                        $set('total', $state * $quantity);
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $pricePerUnit = $get('price_per_unit') ?: 0;
                                        $set('total', $pricePerUnit * $state);
                                    }),
                                TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->prefix('₱')
                                    ->columnSpan(1),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->collapsible()
                            ->cloneable()
                            ->columnSpanFull()
                            ->addActionLabel('Add Item')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Calculate total amount based on all invoice items
                                $total = collect($state ?: [])->sum(function ($item) {
                                    return ($item['price_per_unit'] ?? 0) * ($item['quantity'] ?? 1);
                                });
                                $set('amount', number_format($total, 2, '.', ''));
                            }),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
                    
                Section::make('Summary')
                    ->schema([
                        TextInput::make('amount')
                            ->label('Total Amount')
                            ->required()
                            ->numeric()
                            ->prefix('₱')
                            ->reactive()
                            ->dehydrated(true),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
