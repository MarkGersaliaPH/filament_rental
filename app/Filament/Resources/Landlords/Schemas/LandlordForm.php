<?php

namespace App\Filament\Resources\Landlords\Schemas;

use App\Models\Landlord;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LandlordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    // Personal Information Section
                    Section::make('Personal Information')
                        ->description('Basic personal details of the landlord')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->label('Associated User Account')
                                ->helperText('Link to existing user account'),
                            TextInput::make('first_name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('last_name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            TextInput::make('phone')  
                                ->maxLength(20),
                            DatePicker::make('date_of_birth')
                                ->label('Date of Birth')
                                ->maxDate(now()->subYears(18))
                                ->displayFormat('Y-m-d'),
                        ])->columns(2),

                    // Address Information Section
                    Section::make('Address Information')
                        ->description('Physical address and location details')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            TextInput::make('address')
                                ->label('Street Address')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('city')
                                ->maxLength(100),
                            TextInput::make('state_province')
                                ->label('State/Province')
                                ->maxLength(100),
                            TextInput::make('postal_code')
                                ->label('Postal Code')
                                ->maxLength(20),
                            TextInput::make('country')
                                ->default('US')
                                ->maxLength(10),
                        ])->columns(2),

                    // Business Information Section
                    Section::make('Business Information')
                        ->description('Company and business-related details')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Select::make('business_type')
                                ->options(Landlord::BUSINESS_TYPES)
                                ->default('individual')
                                ->required()
                                ->live()
                                ->columnSpanFull(),
                            TextInput::make('company_name')
                                ->label('Company Name')
                                ->visible(fn ($get) => $get('business_type') !== 'individual')
                                ->maxLength(255),
                            TextInput::make('tax_id')
                                ->label('Tax ID/EIN')
                                ->visible(fn ($get) => $get('business_type') !== 'individual')
                                ->maxLength(50),
                        ])->columns(2),

                 
                ])->columnSpan(2),

                Group::make()->schema([
                    // Status Section
                    Section::make('Status & Verification')
                        ->description('Account verification and status')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Toggle::make('is_verified')
                                ->label('Verified')
                                ->helperText('Has the landlord been verified?'),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->helperText('Is the landlord account active?'),
                            DateTimePicker::make('verified_at')
                                ->label('Verified At')
                                ->visible(fn ($get) => $get('is_verified'))
                                ->disabled()
                                ->dehydrated(),
                        ])->columns(1),

                  

                    // Banking Information Section
                    Section::make('Banking Information')
                        ->description('Payment and banking details')
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            TextInput::make('bank_name')
                                ->label('Bank Name')
                                ->maxLength(255),
                            TextInput::make('account_holder_name')
                                ->label('Account Holder Name')
                                ->maxLength(255),
                        ])->columns(1),

                    // Statistics Section (from database)
                    Section::make('Statistics')
                        ->description('Performance metrics and ratings')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            TextInput::make('average_rating')
                                ->label('Average Rating')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0)
                                ->maxValue(5.00)
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Auto-calculated from reviews'),
                            TextInput::make('total_properties')
                                ->label('Total Properties')
                                ->numeric()
                                ->minValue(0)
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Auto-calculated from properties'),
                            TextInput::make('total_reviews')
                                ->label('Total Reviews')
                                ->numeric()
                                ->minValue(0)
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Auto-calculated from reviews'),
                        ])->columns(1),

                ])->columnSpan(1),

                // Profile Section - Full Width
                Section::make('Profile & Notes')
                    ->description('Additional profile information')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        FileUpload::make('profile_photo')
                        ->image()
    ->avatar()
    ->imageEditor()
                            ->label('Profile Photo'),
                        Textarea::make('bio')
                            ->label('Biography')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])->columns(2)->columnSpanFull(),

            ])->columns(3);
    }
}
