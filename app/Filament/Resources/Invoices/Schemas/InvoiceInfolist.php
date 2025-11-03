<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 🧾 LEFT SIDE — Main Invoice Info
                Group::make()
                    ->columnSpan(2)
                    ->schema([

                        // Header section (From + Invoice Info)
                        Section::make()
                            ->schema([
                                TextEntry::make('billFrom.name')
                                    ->label('From')
                                    ->icon('heroicon-o-building-office')
                                    ->weight('bold'),

                                TextEntry::make('invoice_number')
                                    ->label('Invoice #')
                                    ->icon('heroicon-o-hashtag')
                                    ->copyable()
                                    ->weight('bold') ,

                                TextEntry::make('invoice_date')
                                    ->label('Date Issued')
                                    ->date() ,
                            ])
                            ->extraAttributes(['class' => 'border-b pb-4 mb-4']),

                        // Billing Info
                        Section::make('Billing Information')
                            ->schema([
                                TextEntry::make('billTo.name')
                                    ->label('Bill To')
                                    ->icon('heroicon-o-user')
                                    ->weight('semibold'),

                                TextEntry::make('billable.id')
                                    ->label('Rental Reference')
                                    ->icon('heroicon-o-home')
                                    ->placeholder('-'),
                            ])
                            ->extraAttributes(['class' => 'mb-6']),

                        // Invoice Items (table style)
                        Section::make('Invoice Items')
                            ->schema([
                                RepeatableEntry::make('invoice_items')
                                    ->label("")
                                    ->columns(4)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Item'),

                                        TextEntry::make('price_per_unit')
                                            ->label('Unit Price')
                                            ->money('PHP'),

                                        TextEntry::make('quantity')
                                            ->label('Qty')
                                            ->numeric(),

                                        TextEntry::make('total')
                                            ->label('Line Total')
                                            ->money('PHP')
                                            ->weight('semibold'),
                                    ])
                                    ->columnSpanFull()
                                    ->contained(false) 
                            ])
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-xl p-4 shadow-sm']),

                        // Footer dates
                        Section::make()
                            ->schema([
                                TextEntry::make('due_date')
                                    ->label('Due Date')
                                    ->date()
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime() 
                            ])
                            ->extraAttributes(['class' => 'text-xs text-gray-500 mt-4']),
                    ]),

                // 💰 RIGHT SIDE — Summary & Status
                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Summary')
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->colors([
                                        'warning' => 'pending',
                                        'success' => 'paid',
                                        'danger' => 'cancelled',
                                    ]),

                                TextEntry::make('payment_status')
                                    ->label('Payment Status')
                                    ->badge()
                                    ->colors([
                                        'danger' => 'Unpaid',
                                        'warning' => 'Partial',
                                        'success' => 'Paid',
                                        'gray' => 'Pending',
                                    ]),

                                TextEntry::make('amount')
                                    ->label('Total Amount')
                                    ->money('PHP')
                                    ->size('xl')
                                    ->weight('bold')  
                            ])
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-xl p-4 shadow-sm']),
                    ]),
            ])
            ->columns(3);
    }
}
