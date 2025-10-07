<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class AddressFormSection
{
    /**
     * Create a reusable address form section
     * 
     * @param string $title The title of the section
     * @param int $columnSpan The column span for the section
     * @param bool $collapsible Whether the section should be collapsible
     * @param array $addressTypes Custom address types (optional)
     * @param array $countries Custom country list (optional)
     * @param int $defaultItems Number of default address items
     * @return Section
     */
    public static function make(
        string $title = 'Address Information',
        int $columnSpan = 3,
        bool $collapsible = true,
        array $addressTypes = [],
        array $countries = [],
        int $defaultItems = 0
    ): Section {
        // Default address types
        if (empty($addressTypes)) {
            $addressTypes = [
                'general' => 'General',
                'home' => 'Home',
                'work' => 'Work',
                'billing' => 'Billing',
                'shipping' => 'Shipping',
                'mailing' => 'Mailing',
            ];
        }

        // Default countries
        if (empty($countries)) {
            $countries = [
                'Philippines' => 'Philippines',
                'United States' => 'United States',
                'Canada' => 'Canada',
                'United Kingdom' => 'United Kingdom',
                'Australia' => 'Australia',
                'Japan' => 'Japan',
                'Singapore' => 'Singapore',
                'Malaysia' => 'Malaysia',
                'Thailand' => 'Thailand',
                'Vietnam' => 'Vietnam',
                'Indonesia' => 'Indonesia',
                'China' => 'China',
                'India' => 'India',
                'South Korea' => 'South Korea',
                'Taiwan' => 'Taiwan',
                'Hong Kong' => 'Hong Kong',
            ];
        }

        return Section::make($title)
            ->schema([
                Repeater::make('addresses')
                    ->relationship('addresses')
                    ->schema([
                        Select::make('type')
                            ->options($addressTypes)
                            ->required()
                            ->default('general')
                            ->columnSpan(1),
                        TextInput::make('label')
                            ->label('Address Label')
                            ->placeholder('e.g., Home, Office, Warehouse')
                            ->helperText('Optional custom label for this address')
                            ->columnSpan(1),
                        TextInput::make('street_address_1')
                            ->label('Street Address')
                            ->required()
                            ->placeholder('123 Main Street')
                            ->columnSpan(2),
                        TextInput::make('street_address_2')
                            ->label('Address Line 2')
                            ->placeholder('Apt, Suite, Unit, Building, Floor, etc.')
                            ->columnSpan(2),
                        TextInput::make('city')
                            ->required()
                            ->placeholder('Manila')
                            ->columnSpan(1),
                        TextInput::make('state_province')
                            ->label('State/Province')
                            ->placeholder('Metro Manila')
                            ->columnSpan(1),
                        TextInput::make('postal_code')
                            ->label('Postal/ZIP Code')
                            ->placeholder('1000')
                            ->columnSpan(1),
                        Select::make('country')
                            ->options($countries)
                            ->default('Philippines')
                            ->searchable()
                            ->required()
                            ->columnSpan(1),
                        Toggle::make('is_primary')
                            ->label('Primary Address')
                            ->helperText('Only one address can be primary. This will be handled automatically when saving.')
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive addresses are hidden from most views')
                            ->columnSpan(1),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Additional delivery instructions, landmark, etc.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->collapsible()
                    ->cloneable()
                    ->reorderable(false)
                    ->defaultItems($defaultItems)
                    ->addActionLabel('Add Address')
                    ->deleteAction(
                        fn ($action) => $action->requiresConfirmation()
                    )
                    ->columnSpanFull(),
            ])
            ->columnSpan($columnSpan)
            ->collapsible($collapsible);
    }

    /**
     * Create a simple address section with fewer options
     * 
     * @param string $title The title of the section
     * @param int $columnSpan The column span for the section
     * @return Section
     */
    public static function simple(
        string $title = 'Address Information',
        int $columnSpan = 2
    ): Section {
        return Section::make($title)
            ->schema([
                Repeater::make('addresses')
                    ->relationship('addresses')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'general' => 'General',
                                'home' => 'Home',
                                'work' => 'Work',
                                'billing' => 'Billing',
                            ])
                            ->default('general')
                            ->columnSpan(1),
                        TextInput::make('label')
                            ->label('Label')
                            ->placeholder('Home, Office, etc.')
                            ->columnSpan(1),
                        TextInput::make('street_address_1')
                            ->label('Street Address')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('state_province')
                            ->label('State/Province')
                            ->columnSpan(1),
                        TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->columnSpan(1),
                        Select::make('country')
                            ->options([
                                'Philippines' => 'Philippines',
                                'United States' => 'United States',
                                'Canada' => 'Canada',
                                'United Kingdom' => 'United Kingdom',
                            ])
                            ->default('Philippines')
                            ->columnSpan(1),
                        Toggle::make('is_primary')
                            ->label('Primary')
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->defaultItems(0)
                    ->addActionLabel('Add Address')
                    ->columnSpanFull(),
            ])
            ->columnSpan($columnSpan)
            ->collapsible();
    }

    /**
     * Create a compact single address form (non-repeater)
     * 
     * @param string $prefix Field name prefix (optional)
     * @return array
     */
    public static function single(string $prefix = ''): array
    {
        $prefix = $prefix ? $prefix . '.' : '';

        return [
            TextInput::make($prefix . 'street_address_1')
                ->label('Street Address')
                ->required()
                ->columnSpan(2),
            TextInput::make($prefix . 'street_address_2')
                ->label('Address Line 2')
                ->columnSpan(2),
            TextInput::make($prefix . 'city')
                ->required()
                ->columnSpan(1),
            TextInput::make($prefix . 'state_province')
                ->label('State/Province')
                ->columnSpan(1),
            TextInput::make($prefix . 'postal_code')
                ->label('Postal Code')
                ->columnSpan(1),
            Select::make($prefix . 'country')
                ->options([
                    'Philippines' => 'Philippines',
                    'United States' => 'United States',
                    'Canada' => 'Canada',
                ])
                ->default('Philippines')
                ->columnSpan(1),
        ];
    }

    /**
     * Get predefined address types
     * 
     * @return array
     */
    public static function getAddressTypes(): array
    {
        return [
            'general' => 'General',
            'home' => 'Home',
            'work' => 'Work',
            'billing' => 'Billing',
            'shipping' => 'Shipping',
            'mailing' => 'Mailing',
            'warehouse' => 'Warehouse',
            'branch' => 'Branch Office',
            'headquarters' => 'Headquarters',
        ];
    }

    /**
     * Get extended country list
     * 
     * @return array
     */
    public static function getCountries(): array
    {
        return [
            'Philippines' => 'Philippines',
            'United States' => 'United States',
            'Canada' => 'Canada',
            'United Kingdom' => 'United Kingdom',
            'Australia' => 'Australia',
            'Japan' => 'Japan',
            'Singapore' => 'Singapore',
            'Malaysia' => 'Malaysia',
            'Thailand' => 'Thailand',
            'Vietnam' => 'Vietnam',
            'Indonesia' => 'Indonesia',
            'China' => 'China',
            'India' => 'India',
            'South Korea' => 'South Korea',
            'Taiwan' => 'Taiwan',
            'Hong Kong' => 'Hong Kong',
            'New Zealand' => 'New Zealand',
            'Germany' => 'Germany',
            'France' => 'France',
            'Italy' => 'Italy',
            'Spain' => 'Spain',
            'Netherlands' => 'Netherlands',
            'Belgium' => 'Belgium',
            'Switzerland' => 'Switzerland',
            'Austria' => 'Austria',
            'Sweden' => 'Sweden',
            'Norway' => 'Norway',
            'Denmark' => 'Denmark',
            'Finland' => 'Finland',
        ];
    }

    /**
     * Create an address section for a related model (e.g., customer addresses saved to user)
     * 
     * @param string $relationshipName The relationship name (e.g., 'user')
     * @param string $title The title of the section
     * @param int $columnSpan The column span for the section
     * @param bool $collapsible Whether the section should be collapsible
     * @param array $addressTypes Custom address types (optional)
     * @param array $countries Custom country list (optional)
     * @param int $defaultItems Number of default address items
     * @return Section
     */
    public static function forRelation(
        string $relationshipName,
        string $title = 'Address Information',
        int $columnSpan = 3,
        bool $collapsible = true,
        array $addressTypes = [],
        array $countries = [],
        int $defaultItems = 0
    ): Section {
        // Default address types
        if (empty($addressTypes)) {
            $addressTypes = [
                'general' => 'General',
                'home' => 'Home',
                'work' => 'Work',
                'billing' => 'Billing',
                'shipping' => 'Shipping',
                'mailing' => 'Mailing',
            ];
        }

        // Default countries
        if (empty($countries)) {
            $countries = static::getCountries();
        }

        return Section::make($title)
            ->schema([
                Repeater::make("{$relationshipName}.addresses")
                    ->relationship("{$relationshipName}")
                    ->schema([
                        Select::make('type')
                            ->options($addressTypes)
                            ->required()
                            ->default('general')
                            ->columnSpan(1),
                        TextInput::make('label')
                            ->label('Address Label')
                            ->placeholder('e.g., Home, Office, Warehouse')
                            ->helperText('Optional custom label for this address')
                            ->columnSpan(1),
                        TextInput::make('street_address_1')
                            ->label('Street Address')
                            ->required()
                            ->placeholder('123 Main Street')
                            ->columnSpan(2),
                        TextInput::make('street_address_2')
                            ->label('Address Line 2')
                            ->placeholder('Apt, Suite, Unit, Building, Floor, etc.')
                            ->columnSpan(2),
                        TextInput::make('city')
                            ->required()
                            ->placeholder('Manila')
                            ->columnSpan(1),
                        TextInput::make('state_province')
                            ->label('State/Province')
                            ->placeholder('Metro Manila')
                            ->columnSpan(1),
                        TextInput::make('postal_code')
                            ->label('Postal/ZIP Code')
                            ->placeholder('1000')
                            ->columnSpan(1),
                        Select::make('country')
                            ->options($countries)
                            ->default('Philippines')
                            ->searchable()
                            ->required()
                            ->columnSpan(1),
                        Toggle::make('is_primary')
                            ->label('Primary Address')
                            ->helperText('Only one address can be primary. This will be handled automatically when saving.')
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive addresses are hidden from most views')
                            ->columnSpan(1),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Additional delivery instructions, landmark, etc.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->collapsible()
                    ->cloneable()
                    ->reorderable(false)
                    ->defaultItems($defaultItems)
                    ->addActionLabel('Add Address')
                    ->deleteAction(
                        fn ($action) => $action->requiresConfirmation()
                    )
                    ->columnSpanFull(),
            ])
            ->columnSpan($columnSpan)
            ->collapsible($collapsible);
    }
}
