<?php

namespace App\Filament\Resources\Rentals\Schemas;

use App\Models\Property;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Carbon\Carbon;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class RentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([

                    Section::make('Owner and Renter')->schema([
                        Select::make('property_id')
                            ->relationship('property', 'title')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                if ($state) {
                                    $property = Property::find($state);
                                    if ($property && $property->rent_amount) {
                                        $set('rent_amount', $property->rent_amount);
                                        $set('security_deposit', $property->security_deposit);
                                        self::calculateTotalAmount($set, $get);
                                    }
                                }
                            }),
                        Select::make('customer_id')
                            ->relationship('customer', 'first_name')
                            ->required(),
                    ]),

                    Section::make('Duration & Amounts')->schema([
                        DatePicker::make('start_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotalAmount($set, $get)),
                        DatePicker::make('end_date')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotalAmount($set, $get)),
                        TextInput::make('rent_amount')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotalAmount($set, $get)),
                        TextInput::make('security_deposit')
                            ->numeric(),
                        Select::make('payment_frequency')
                            ->options(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'])
                            ->default('monthly')
                            ->columnSpanFull()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateTotalAmount($set, $get)),
                            
                        DatePicker::make('next_due_date'),
                        TextInput::make('total_amount')
                            ->label('Total Contract Amount')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                    ])->columns(2),

                    Section::make()->schema([

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
                    


                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make('Status')->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'terminated' => 'Terminated',
                            ])
                            ->default('pending')
                            ->required(),
                        Select::make('payment_status')
                            ->options(['unpaid' => 'Unpaid', 'partial' => 'Partial', 'paid' => 'Paid', 'overdue' => 'Overdue'])
                            ->default('unpaid')
                            ->required(),
                    ]),
                    Section::make('Contract Document')->schema([
                        FileUpload::make('contract_file'),
                    ]),
                    Section::make('Approval Details')->schema([
                        DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approvedBy', 'name')
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(1)
                    ->visible(fn ($record) => $record && $record->approved_at),
                    
                    Section::make('Termination Details')->schema([
                        DateTimePicker::make('terminated_at'),
                        Textarea::make('termination_reason')
                            ->columnSpanFull(),
                    ])->columns(1)
                    ->visible(fn ($record) => $record && $record->terminated_at)
                ])->columnSpan(1),


            ])->columns(3);
    }

    private static function calculateTotalAmount(Set $set, Get $get): void
    {
        $startDate = $get('start_date');
        $endDate = $get('end_date');
        $rentAmount = (float) ($get('rent_amount') ?? 0);
        $securityDeposit = (float) ($get('security_deposit') ?? 0);
        $paymentFrequency = $get('payment_frequency') ?? 'monthly';

        if (!$startDate || !$endDate || $rentAmount <= 0) {
            $set('total_amount', 0);
            return;
        }

        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            if ($end->lte($start)) {
                $set('total_amount', 0);
                return;
            }

            // Calculate periods based on payment frequency
            $periods = match ($paymentFrequency) {
                'daily' => $start->diffInDays($end),
                'weekly' => $start->diffInWeeks($end),
                'monthly' => $start->diffInMonths($end),
                'yearly' => $start->diffInYears($end),
                default => $start->diffInMonths($end)
            };

            // Calculate total amount
            // Using your requested formula: (rent_amount * periods) - security_deposit
            $totalAmount = ($rentAmount * $periods) - $securityDeposit;
            
            // Ensure total amount is not negative
            $totalAmount = max(0, $totalAmount);

            $set('total_amount', number_format($totalAmount, 2, '.', ''));
        } catch (\Exception $e) {
            $set('total_amount', 0);
        }
    }
}
