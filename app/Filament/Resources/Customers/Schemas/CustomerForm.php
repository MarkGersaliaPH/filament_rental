<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Filament\Components\AddressFormSection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('last_name')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('phone')
                            ->tel()
                            ->columnSpan(1),
                        DatePicker::make('date_of_birth')
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active Customer')
                            ->required()
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
                    ->collapsible(),
                    
                Section::make('System Information')
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->helperText('When the customer verified their email address')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1)
                    ->collapsible(),
                    
                AddressFormSection::make( 
                    title: 'Customer Addresses',
                    columnSpan: 3,
                    collapsible: true,
                    addressTypes: [
                        'home' => 'Home',
                        'work' => 'Work',
                        'billing' => 'Billing',
                        'shipping' => 'Shipping',
                        'mailing' => 'Mailing',
                    ],
                    defaultItems: 0
                ),
                    
            ])->columns(3);
    }
}
