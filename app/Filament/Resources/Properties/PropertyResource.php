<?php

namespace App\Filament\Resources\Properties;

use App\Filament\Resources\Properties\Pages\CreateProperty;
use App\Filament\Resources\Properties\Pages\EditProperty;
use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Filament\Resources\Properties\Pages\ViewProperty;
use App\Filament\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Resources\Properties\Tables\PropertiesTable;
use App\Models\Property;
use BackedEnum;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;


    protected static string|UnitEnum|null $navigationGroup = 'Rental Management';

    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertiesTable::configure($table);
    }



    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Group::make(
                [
                    // 🖼️ Media
                    Section::make('Media & Photos')
                        ->schema([
                            
                            ImageEntry::make('images')
                                ->label('Property Photos')
                                ->stacked()
                                ->square(),
                                MediaAction::make('View Video')  
                                ->icon('heroicon-o-video-camera')
                                ->media(fn($record) => $record->video_url)
                                ->autoplay(),
                                MediaAction::make('View Virtual Tour') 
                                ->icon('heroicon-o-video-camera')
                                ->media(fn($record) => $record->virtual_tour_url)
                                ->autoplay(),
                             
                        ]),
                    Section::make('Property Details')
                        ->schema([
                            TextEntry::make('title')->label('Property Title')->columnSpanFull(),
                            TextEntry::make('slug')->label('URL Slug')->copyable()->columnSpanFull(),
                            TextEntry::make('description')->label('Description')->markdown()->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Physical Specifications')
                        ->schema([
                            TextEntry::make('bedrooms')->label('Bedrooms'),
                            TextEntry::make('bathrooms')->label('Bathrooms'),
                            TextEntry::make('area_sqft')->label('Area (sq ft)'),
                            TextEntry::make('area_sqm')->label('Area (sq m)'),
                            TextEntry::make('floor_number')->label('Floor Number'),
                            TextEntry::make('total_floors')->label('Total Floors'),
                            TextEntry::make('built_year')->label('Built Year'),
                        ])
                        ->columns(2),
                    // 📅 Rental Terms
                    Section::make('Rental Terms & Availability')
                        ->schema([
                            TextEntry::make('available_from')->label('Available From')->date(),
                            TextEntry::make('available_until')->label('Available Until')->date(),
                            TextEntry::make('minimum_stay_days')->label('Minimum Stay (Days)'),
                            TextEntry::make('maximum_stay_days')->label('Maximum Stay (Days)'),
                        ])
                        ->columns(2),
                    // 💰 Pricing
                    Section::make('Pricing & Fees')
                        ->schema([
                            TextEntry::make('rent_amount')->label('Rent Amount')->money('USD'),
                            TextEntry::make('rent_period')->label('Billing Period'),
                            TextEntry::make('security_deposit')->label('Security Deposit')->money('USD'),
                            TextEntry::make('cleaning_fee')->label('Cleaning Fee')->money('USD'),
                        ])
                        ->columns(2),



                    // 📍 Location
                    Section::make('Location Details')
                        ->schema([
                            TextEntry::make('address')->label('Address')->columnSpanFull(),
                            TextEntry::make('city')->label('City'),
                            TextEntry::make('state_province')->label('State / Province'),
                            TextEntry::make('postal_code')->label('Postal Code'),
                            TextEntry::make('country')->label('Country'),
                            TextEntry::make('neighborhood')->label('Neighborhood'),
                            TextEntry::make('latitude')->label('Latitude'),
                            TextEntry::make('longitude')->label('Longitude'),
                        ])
                        ->columns(2),


                ]
            )->columnSpan(2),

            Group::make([
                Section::make('Property Type & Status')
                    ->schema([
                        TextEntry::make('type')->label('Type')->badge(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state) => match ($state) {
                                'available' => 'success',
                                'rented' => 'danger',
                                default => 'gray',
                            }),
                        IconEntry::make('is_featured')->label('Featured')->boolean(),
                    ])
                    ->columns(2),
                Section::make('Owner')
                    ->schema([
                        TextEntry::make('landlord.first_name')->label('Owner Name'),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextEntry::make('contact_name')->label('Contact Person'),
                        TextEntry::make('contact_phone')->label('Phone Number'),
                        TextEntry::make('contact_email')
                            ->label('Email')
                            ->url(fn($state) => "mailto:$state", shouldOpenInNewTab: true)
                            ->icon('heroicon-o-envelope'),
                    ])
                    ->columns(2),            // 🧱 Property Specs


                Section::make('Amenities')
                    ->schema([
                        TextEntry::make('amenities')->label('Available Amenities')->columns(3),
                    ])
                    ->columns(1),
                Section::make('Features')
                    ->schema([
                        IconEntry::make('furnished')->label('Furnished')->boolean(),
                        IconEntry::make('pet_friendly')->label('Pet Friendly')->boolean(),
                        IconEntry::make('smoking_allowed')->label('Smoking Allowed')->boolean(),
                    ])
                    ->columns(3),

                Section::make('Appliances & Utilities')
                    ->schema([
                        TextEntry::make('appliances')->label('Appliances')->columns(2),
                        TextEntry::make('utilities_included')->label('Utilities Included')->columns(2),
                    ])
                    ->columns(2),

                // 🌐 SEO
                Section::make('SEO & Marketing')
                    ->schema([
                        TextEntry::make('meta_title')->label('SEO Title'),
                        TextEntry::make('meta_description')->label('SEO Description'),
                        TextEntry::make('tags')->label('Tags')->badge()->color('info'),
                    ])
                    ->columns(2),

            ])->columnSpan(1),




        ])->columns(3);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
            'create' => CreateProperty::route('/create'),
            'view' => ViewProperty::route('/{record}'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getFormActions(): array
    {
        return []; // removes default Create / Cancel buttons
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
