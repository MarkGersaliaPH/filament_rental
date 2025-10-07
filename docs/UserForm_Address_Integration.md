# UserForm Address Integration

## Overview
The UserForm now includes comprehensive address management functionality using the HasAddress trait and Address model through a polymorphic relationship.

## What Was Implemented

### 1. **UserForm Address Fields** (`app/Filament/Resources/Users/Schemas/UserForm.php`)

Added a new "Address Information" section with a repeater that includes:

#### Address Fields:
- **Type** - Select dropdown with options (general, home, work, billing, shipping, mailing)
- **Label** - Custom label for the address (e.g., "Home", "Office", "Warehouse")
- **Street Address** - Primary street address (required)
- **Address Line 2** - Secondary address line (optional)
- **City** - City name (required)
- **State/Province** - State or province (optional)
- **Postal/ZIP Code** - Postal code (optional)
- **Country** - Country dropdown with common countries (defaults to Philippines)
- **Primary Address** - Toggle to mark as primary address
- **Active** - Toggle to mark address as active (defaults to true)
- **Notes** - Additional notes for the address

#### Form Features:
- **Repeater Layout** - Users can add multiple addresses
- **Collapsible Items** - Each address can be collapsed for better UI
- **Cloneable** - Addresses can be duplicated and modified
- **No Default Items** - Form starts with 0 addresses, user adds as needed
- **4-Column Layout** - Responsive grid layout for form fields

### 2. **Address Model Updates** (`app/Models/Address.php`)

Added automatic primary address handling:

```php
protected static function booted()
{
    static::saving(function (Address $address) {
        // If this address is being marked as primary
        if ($address->is_primary && $address->addressable_id && $address->addressable_type) {
            // Unset other primary addresses for the same addressable entity
            static::where('addressable_type', $address->addressable_type)
                ->where('addressable_id', $address->addressable_id)
                ->where('id', '!=', $address->id ?? 0)
                ->update(['is_primary' => false]);
        }
    });
}
```

### 3. **User Model** (`app/Models/User.php`)

Already includes the HasAddress trait:
- `use HasAddress;` - Provides all address relationship methods

## How It Works

### **Form Interaction**
1. User opens the User edit/create form
2. Clicks "Add Address" in the Address Information section  
3. Fills out address fields
4. Can mark one address as "Primary"
5. Can add multiple addresses of different types
6. Form saves addresses to the `addresses` table via polymorphic relationship

### **Database Storage**
- Addresses are stored in the `addresses` table
- Linked to users via `addressable_type` = 'App\\Models\\User' and `addressable_id` = user ID
- Primary address logic is handled automatically

### **Accessing Addresses in Code**

```php
$user = User::find(1);

// Get all addresses
$addresses = $user->addresses;

// Get primary address
$primaryAddress = $user->primaryAddress;

// Get specific address types
$homeAddress = $user->addressesByType('home')->first();
$billingAddress = $user->billingAddress;
$shippingAddress = $user->shippingAddress;

// Add address programmatically
$user->addAddress([
    'type' => 'home',
    'label' => 'My Home',
    'street_address_1' => '123 Main St',
    'city' => 'Manila',
    'country' => 'Philippines',
    'is_primary' => true
]);

// Get formatted address
$formatted = $user->getFormattedAddress('billing');
echo $formatted;
```

### **Address Types Available**
- `general` - General address (default)
- `home` - Home address  
- `work` - Work/office address
- `billing` - Billing address
- `shipping` - Shipping/delivery address
- `mailing` - Mailing address

## Benefits

### **For Users**
✅ **Multiple Addresses** - Can store home, work, billing addresses separately  
✅ **Organized Interface** - Clean, collapsible form sections  
✅ **Flexible Labels** - Custom labels for easy identification  
✅ **Primary Address** - Automatic handling of primary address selection  
✅ **International Support** - Support for multiple countries  

### **For Developers**  
✅ **Polymorphic Design** - Same address system works for any model  
✅ **Rich API** - Comprehensive methods for address management  
✅ **Automatic Validation** - Primary address logic handled automatically  
✅ **Database Optimization** - Proper indexes and relationships  
✅ **Reusable Components** - Can be applied to other models easily  

## Form Layout

```
┌─────────────────────────────────────────────────────────────────┐
│                    Address Information                           │
│ ┌─────────────────────────────────────────────────────────────┐ │
│ │ Add Address                                                 │ │  
│ │ ┌─── Address 1 ─────────────────────────────────────────┐   │ │
│ │ │ Type: [Home ▼]        Label: [My Home        ]       │   │ │
│ │ │ Street Address: [123 Main Street              ]       │   │ │  
│ │ │ Address Line 2: [Apt 1A                       ]       │   │ │
│ │ │ City: [Manila    ] State: [Metro Manila] ZIP: [1000] │   │ │
│ │ │ Country: [Philippines ▼]                             │   │ │
│ │ │ ☑ Primary Address    ☑ Active                        │   │ │
│ │ │ Notes: [Near the mall                        ]       │   │ │
│ │ └─────────────────────────────────────────────────────────┘   │ │
│ │                                                             │ │
│ │ + Add Address                                               │ │
│ └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Testing

You can now:
1. Go to Users management in Filament
2. Create or edit a user  
3. Scroll to "Address Information" section
4. Add multiple addresses with different types
5. Mark one as primary
6. Save and verify addresses are stored correctly

The address data will be automatically saved to the `addresses` table and linked to the user via the polymorphic relationship.