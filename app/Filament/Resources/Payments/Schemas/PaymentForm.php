<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make()->schema([

                        TextInput::make('reference_number')->columnSpanFull(),
                        Select::make('invoice_id')->relationship('invoice', 'invoice_number')->columnSpanFull(),
                        Select::make('payer_id')->relationship('payer', 'email'),
                        Select::make('receiver_id')->relationship('receiver', 'email'),
                    ])->columns(2),
                    Section::make()->schema([
                        Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Bank transfer',
                                'gcash' => 'Gcash',
                                'paypal' => 'Paypal',
                                'paymaya' => 'Paymaya',
                            ])
                            ->default('bank_transfer')
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                    ]),
                ])->columnSpan(2),
                Group::make([
                    Section::make()->schema([
                        Select::make('status')
                            ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'failed' => 'Failed', 'refunded' => 'Refunded'])
                            ->default('pending')
                            ->required(),
                        DateTimePicker::make('paid_at'),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
