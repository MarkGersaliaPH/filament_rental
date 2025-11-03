<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Payment Details')
                        ->description('Core payment information and identifiers.')
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextEntry::make('invoice.invoice_number')
                                        ->label('Invoice Number')
                                        ->icon('heroicon-o-document-text')
                                        ->copyable()
                                        ->tooltip('Click to copy invoice number'),

                                    TextEntry::make('reference_number')
                                        ->label('Reference No.')
                                        ->placeholder('-')
                                        ->icon('heroicon-o-hashtag'),


                                ]),
                        ]),
                    Section::make('Participants')
                        ->icon('heroicon-o-user-group')
                        ->description('Entities involved in this payment.')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextEntry::make('payer.email')
                                        ->label('Payer')
                                        ->numeric()
                                        ->placeholder('-')
                                        ->icon('heroicon-o-user'),

                                    TextEntry::make('receiver.email')
                                        ->label('Receiver')
                                        ->numeric()
                                        ->placeholder('-')
                                        ->icon('heroicon-o-user-circle'),
                                ]),
                        ]),
                    Section::make('Transaction Information')
                        ->icon('heroicon-o-banknotes')
                        ->description('Payment method, amount, and date.')
                        ->schema([  
                                    TextEntry::make('payment_method')
                                        ->label('Payment Method')
                                        ->badge()
                                        ->placeholder('-'), 
                                    TextEntry::make('amount')
                                        ->label('Amount')
                                        ->money('PHP') // or change currency as needed
                                        ->numeric(), 
                        ]),
                ])->columnSpan(2),

                Group::make([
                    Section::make()->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'primary' => 'Pending',
                                'success' => 'Completed',
                                'warning' => 'Processing',
                                'danger' => 'Failed',
                            ]),
                        TextEntry::make('paid_at')
                            ->label('Paid At')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                ])->columnSpan(1)



            ])->columns(3);
    }
}
