# AddressFormSection - Reusable Component

## Overview
The `AddressFormSection` is a reusable Filament form component that provides comprehensive address management functionality for any model that uses the `HasAddress` trait.

## Location
`app/Filament/Components/AddressFormSection.php`

## Features
✅ **Reusable** - Can be added to any form schema  
✅ **Customizable** - Support for custom address types, countries, and styling  
✅ **Multiple Variants** - Full, simple, and single address formats  
✅ **Polymorphic Ready** - Works with any model using HasAddress trait  
✅ **User-friendly** - Collapsible, cloneable, with validation  

## Usage Examples

### 1. **Basic Usage**
```php
use App\Filament\Components\AddressFormSection;

// In your form schema
AddressFormSection::make()
```

### 2. **Full Customization**
```php
AddressFormSection::make(
    title: 'Customer Addresses',
    columnSpan: 3,
    collapsible: true,
    addressTypes: [
        'home' => 'Home',
        'work' => 'Work', 
        'billing' => 'Billing',
    ],
    countries: AddressFormSection::getCountries(),
    defaultItems: 1
)
```

### 3. **Simple Version**
```php
// For forms with limited space
AddressFormSection::simple(
    title: 'Address',
    columnSpan: 2
)
```

### 4. **Single Address Fields** (Non-repeater)
```php
// Get array of individual form fields
$addressFields = AddressFormSection::single();
// Or with prefix
$addressFields = AddressFormSection::single('shipping');
```

## Method Reference

### `make()` - Full Address Section
```php
public static function make(
    string $title = 'Address Information',
    int $columnSpan = 3,
    bool $collapsible = true,
    array $addressTypes = [],
    array $countries = [],
    int $defaultItems = 0
): Section
```

**Parameters:**
- `$title` - Section title
- `$columnSpan` - Grid column span (1-12)
- `$collapsible` - Whether section can be collapsed
- `$addressTypes` - Custom address types array
- `$countries` - Custom countries array  
- `$defaultItems` - Number of default address items

**Features:**
- 4-column responsive layout
- All address fields included
- Collapsible address items
- Cloneable addresses
- Delete confirmation
- Helper texts and placeholders

### `simple()` - Simplified Address Section
```php
public static function simple(
    string $title = 'Address Information', 
    int $columnSpan = 2
): Section
```

**Features:**
- 2-column layout
- Essential fields only
- Fewer address types
- Compact design
- Good for smaller forms

### `single()` - Individual Address Fields
```php
public static function single(string $prefix = ''): array
```

**Returns:** Array of form components for embedding in existing forms

**Use Case:** When you need address fields but not as a repeater

### Helper Methods

#### `getAddressTypes()` - Predefined Address Types
```php
public static function getAddressTypes(): array
```

Returns:
```php
[
    'general' => 'General',
    'home' => 'Home',
    'work' => 'Work',
    'billing' => 'Billing',
    'shipping' => 'Shipping',
    'mailing' => 'Mailing',
    'warehouse' => 'Warehouse',
    'branch' => 'Branch Office',
    'headquarters' => 'Headquarters',
]
```

#### `getCountries()` - Extended Country List
```php
public static function getCountries(): array
```

Returns 30+ countries including Philippines, US, Canada, UK, Asian countries, and European countries.

## Form Fields Included

### **Full Version Fields:**
- **Type** - Select dropdown (general, home, work, etc.)
- **Label** - Custom address label
- **Street Address** - Primary address line (required)
- **Address Line 2** - Secondary address line
- **City** - City name (required)
- **State/Province** - State or province
- **Postal/ZIP Code** - Postal code
- **Country** - Country dropdown (searchable)
- **Primary Address** - Toggle for primary address
- **Active** - Toggle for active status
- **Notes** - Additional notes/instructions

### **Simple Version Fields:**
- Type, Label, Street Address, City, State/Province, Postal Code, Country, Primary, Active

### **Single Version Fields:**
- Street Address 1 & 2, City, State/Province, Postal Code, Country

## Implementation Examples

### UserForm Implementation
```php
<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Components\AddressFormSection;
// ... other imports

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ... other sections
            
            AddressFormSection::make(
                title: 'Address Information',
                columnSpan: 3,
                collapsible: true,
                defaultItems: 0
            ),
            
        ])->columns(3);
    }
}
```

### CustomerForm Implementation
```php
<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Filament\Components\AddressFormSection;
// ... other imports

class CustomerForm  
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ... other sections
            
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
```

## Model Requirements

To use AddressFormSection, your model must:

1. **Use HasAddress Trait**
```php
use App\Traits\HasAddress;

class YourModel extends Model
{
    use HasAddress;
    // ...
}
```

2. **Have Proper Relationship**
The trait provides `addresses()` relationship method automatically.

3. **Database Migration**
The `addresses` table must exist (created when you set up the Address model).

## Customization Options

### Custom Address Types
```php
AddressFormSection::make(
    addressTypes: [
        'primary' => 'Primary Location',
        'secondary' => 'Secondary Location',
        'emergency' => 'Emergency Contact',
    ]
)
```

### Custom Countries
```php
AddressFormSection::make(
    countries: [
        'Philippines' => 'Philippines',
        'Singapore' => 'Singapore', 
        'Malaysia' => 'Malaysia',
    ]
)
```

### Styling Options
```php
AddressFormSection::make(
    columnSpan: 2,        // Adjust width
    collapsible: false,   // Always expanded
    defaultItems: 1       // Start with 1 address
)
```

## Benefits

### **For Developers**
✅ **DRY Principle** - Write once, use everywhere  
✅ **Consistent UI** - Same address interface across all forms  
✅ **Easy Maintenance** - Update once, applies everywhere  
✅ **Flexible** - Multiple variants for different use cases  
✅ **Type Safety** - Proper return types and documentation  

### **For Users**
✅ **Familiar Interface** - Same address form everywhere  
✅ **Rich Functionality** - All address management features  
✅ **Responsive Design** - Works on all screen sizes  
✅ **Validation** - Proper field validation and helpers  

## Future Enhancements

Potential improvements you could add:
- **Address Validation** - Integration with address validation APIs
- **Geocoding** - Automatic lat/lng from address
- **Address Autocomplete** - Google Places API integration
- **Map Preview** - Show address on map
- **Address Templates** - Predefined address formats by country
- **Bulk Import** - CSV address import functionality

## Testing

You can test the component by:
1. Adding it to any form schema
2. Creating/editing records with addresses
3. Verifying addresses save correctly
4. Testing different variants (full, simple, single)
5. Checking responsiveness on different screen sizes

The component automatically handles the polymorphic relationship and primary address logic through the Address model's boot method.