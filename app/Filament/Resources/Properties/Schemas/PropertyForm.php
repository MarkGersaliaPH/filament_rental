<?php

namespace App\Filament\Resources\Properties\Schemas;

use App\Models\Property;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor; 
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make()
                    ->submitAction(new HtmlString('<button type="submit">Submit</button>'))
                    ->skippable()
                    ->steps([
                    Step::make('Basic Information')
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->icon(Heroicon::HomeModern)
                        ->description('Essential property details')
                        ->schema([
                            Section::make('Property Details')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Property Title')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state)))
                                        ->columnSpanFull(),
                                        
                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignorable: fn ($record) => $record)
                                        ->helperText('Auto-generated from title')
                                        ->columnSpanFull(),
                                        
                                    RichEditor::make('description')
                                        ->label('Property Description')
                                        ->required()
                                        ->maxLength(2000)
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                        ])
                                        ->columnSpanFull(),
                                ])->columns(2),
                                
                            Section::make('Property Type & Status')
                                ->schema([
                                    Select::make('type')
                                        ->label('Property Type')
                                        ->options(Property::TYPES)
                                        ->required()
                                        ->native(false),
                                        
                                    Select::make('status')
                                        ->label('Current Status')
                                        ->options(Property::STATUSES)
                                        ->default('available')
                                        ->required()
                                        ->native(false),
                                        
                                    Toggle::make('is_featured')
                                        ->label('Featured Property')
                                        ->helperText('Highlight this property in listings')
                                        ->columnSpanFull(),
                                ])->columns(2),

                            Section::make("Owner")->schema([
                                Select::make('owner_id')->relationship('landlord','first_name')
                            ]),
                                
                            Section::make('Contact Information')
                                ->schema([
                                    TextInput::make('contact_name')
                                        ->label('Contact Person')
                                        ->maxLength(255),
                                    TextInput::make('contact_phone')
                                        ->label('Phone Number') 
                                        ->maxLength(20),
                                    TextInput::make('contact_email')
                                        ->label('Contact Email')
                                        ->email()
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                ])->columns(2),
                        ])->columns(1),
                    Step::make('Property Specifications')
                        ->icon(Heroicon::ListBullet)
                        ->description('Physical details and location')
                        ->schema([
                            Section::make('Physical Specifications')
                                ->schema([
                                    TextInput::make('bedrooms')
                                        ->label('Number of Bedrooms')
                                        ->required()
                                        ->numeric()
                                        ->default(0)
                                        ->helperText('Use 0 for studio apartments'),
                                    TextInput::make('bathrooms')
                                        ->label('Number of Bathrooms')
                                        ->required()
                                        ->numeric()
                                        ->step(0.5)
                                        ->default(1),
                                    TextInput::make('area_sqft')
                                        ->label('Area (Square Feet)')
                                        ->numeric()
                                        ->suffix('sq ft'),
                                    TextInput::make('area_sqm')
                                        ->label('Area (Square Meters)')
                                        ->numeric()
                                        ->suffix('sq m'),
                                    TextInput::make('floor_number')
                                        ->label('Floor Number')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100),
                                    TextInput::make('total_floors')
                                        ->label('Total Floors in Building')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100),
                                    TextInput::make('built_year')
                                        ->label('Year Built')
                                        ->numeric()
                                        ->minValue(1800)
                                        ->maxValue(date('Y')),
                                ])->columns(2),
                                
                            Section::make('Property Features')
                                ->schema([
                                    Toggle::make('furnished')
                                        ->label('Furnished Property')
                                        ->helperText('Property comes with furniture'),
                                    Toggle::make('pet_friendly')
                                        ->label('Pet Friendly')
                                        ->helperText('Pets are allowed'),
                                    Toggle::make('smoking_allowed')
                                        ->label('Smoking Allowed')
                                        ->helperText('Smoking is permitted'),
                                ])->columns(3),
                                
                            Section::make('Location Details')
                                ->schema([
                                    TextInput::make('address')
                                        ->label('Street Address')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    TextInput::make('city')
                                        ->label('City')
                                        ->required()
                                        ->maxLength(100),
                                    TextInput::make('state_province')
                                        ->label('State/Province')
                                        ->maxLength(100),
                                    TextInput::make('postal_code')
                                        ->label('ZIP/Postal Code')
                                        ->maxLength(20),
                                    Select::make('country')
                                        ->label('Country')
                                        ->options([
                                            'US' => 'United States',
                                            'CA' => 'Canada',
                                            'GB' => 'United Kingdom',
                                            'AU' => 'Australia',
                                        ])
                                        ->required()
                                        ->default('US')
                                        ->native(false),
                                    TextInput::make('neighborhood')
                                        ->label('Neighborhood')
                                        ->maxLength(100),
                                    TextInput::make('latitude')
                                        ->label('Latitude')
                                        ->numeric()
                                        ->step(0.000001)
                                        ->helperText('GPS coordinates for mapping'),
                                    TextInput::make('longitude')
                                        ->label('Longitude')
                                        ->numeric()
                                        ->step(0.000001)
                                        ->helperText('GPS coordinates for mapping'),
                                ])->columns(2),
                        ])->columns(1),
                    Step::make('Rental Terms & Availability')
                        ->icon(Heroicon::Calendar)
                        ->description('Availability and rental requirements')
                        ->schema([
                            Section::make('Availability Dates')
                                ->schema([
                                    DatePicker::make('available_from')
                                        ->label('Available From')
                                        ->native(false)
                                        ->displayFormat('M d, Y')
                                        ->helperText('When can tenants move in?'),
                                    DatePicker::make('available_until')
                                        ->label('Available Until')
                                        ->native(false)
                                        ->displayFormat('M d, Y')
                                        ->helperText('Leave empty for indefinite availability'),
                                ])->columns(2),
                                
                            Section::make('Rental Requirements')
                                ->schema([
                                    TextInput::make('minimum_stay_days')
                                        ->label('Minimum Stay (Days)')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(3650)
                                        ->default(30)
                                        ->suffix('days'),
                                    TextInput::make('maximum_stay_days')
                                        ->label('Maximum Stay (Days)')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(3650)
                                        ->suffix('days')
                                        ->helperText('Leave empty for no limit'),
                                ])->columns(2),
                        ])->columns(1),
                    Step::make('Pricing & Features')
                        ->icon(Heroicon::OutlinedCurrencyDollar)
                        ->description('Rent, fees, and property amenities')
                        ->schema([
                            Section::make('Rental Pricing')
                                ->schema([
                                    TextInput::make('rent_amount')
                                        ->label('Rent Amount')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100000)
                                        ->prefix('$')
                                        ->step(0.01),
                                    Select::make('rent_period')
                                        ->label('Billing Period')
                                        ->options(Property::RENT_PERIODS)
                                        ->default('monthly')
                                        ->required()
                                        ->native(false),
                                    TextInput::make('security_deposit')
                                        ->label('Security Deposit')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100000)
                                        ->prefix('$')
                                        ->step(0.01),
                                    TextInput::make('cleaning_fee')
                                        ->label('Cleaning Fee')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(10000)
                                        ->prefix('$')
                                        ->step(0.01),
                                ])->columns(2),
                                
                            Section::make('Property Amenities')
                                ->schema([
                                    CheckboxList::make('amenities')
                                        ->label('Available Amenities')
                                        ->options(Property::AMENITIES)
                                        ->columns(3)
                                        ->gridDirection('row')
                                        ->columnSpanFull(),
                                ])->columns(1),
                                
                            Section::make('Appliances & Utilities')
                                ->schema([
                                    CheckboxList::make('appliances')
                                        ->label('Included Appliances')
                                        ->options(Property::APPLIANCES)
                                        ->columns(2)
                                        ->gridDirection('row'),
                                    CheckboxList::make('utilities_included')
                                        ->label('Utilities Included')
                                        ->options([
                                            'electricity' => 'Electricity',
                                            'water' => 'Water/Sewer',
                                            'gas' => 'Natural Gas',
                                            'internet' => 'Internet',
                                            'cable' => 'Cable TV',
                                            'trash' => 'Trash Collection',
                                            'heating' => 'Heating',
                                            'cooling' => 'Air Conditioning',
                                        ])
                                        ->columns(2)
                                        ->gridDirection('row'),
                                ])->columns(2),
                        ])->columns(1),
                    Step::make('Media & Photos')
                        ->icon(Heroicon::OutlinedPhoto)
                        ->description('Property images and virtual content')
                        ->schema([
                            Section::make('Property Images')
                                ->schema([
                                    FileUpload::make('images')
                                        ->label('Property Photos')
                                        ->image()
                                        ->multiple()
                                        ->maxFiles(10)
                                        ->maxSize(5120) // 5MB
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([
                                            '16:9',
                                            '4:3',
                                            '1:1',
                                        ])
                                        ->helperText('Upload up to 10 high-quality photos. First image will be used as main photo.')
                                        ->columnSpanFull(),
                                ])->columns(1),
                                
                            Section::make('Virtual Content')
                                ->schema([
                                    TextInput::make('video_url')
                                        ->label('Property Video URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->helperText('YouTube, Vimeo, or other video platform URL'),
                                    TextInput::make('virtual_tour_url')
                                        ->label('Virtual Tour URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->helperText('360° virtual tour or interactive walkthrough URL'),
                                ])->columns(1),
                        ])->columns(1),
                        
                    Step::make('SEO & Marketing')
                        ->icon(Heroicon::GlobeAlt)
                        ->description('Search optimization and marketing tags')
                        ->schema([
                            Section::make('Search Engine Optimization')
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label('SEO Title')
                                        ->maxLength(60)
                                        ->helperText('Leave empty to auto-generate from property title')
                                        ->columnSpanFull(),
                                    Textarea::make('meta_description')
                                        ->label('SEO Description')
                                        ->maxLength(160)
                                        ->rows(3)
                                        ->helperText('Brief description for search engines (160 characters max)')
                                        ->columnSpanFull(),
                                ])->columns(1),
                                
                            Section::make('Marketing Tags')
                                ->schema([
                                    TagsInput::make('tags')
                                        ->label('Property Tags')
                                        ->suggestions([
                                            'modern', 'luxury', 'furnished', 'pet-friendly', 
                                            'downtown', 'quiet', 'spacious', 'updated', 
                                            'parking', 'balcony', 'view', 'walkable'
                                        ])
                                        ->helperText('Add keywords to help people find your property')
                                        ->columnSpanFull(),
                                ])->columns(1),
                                
                        
                        ])->columns(1),
                ]),

            ])->columns(1);
    }
}
