# Customer-User Address Integration

## Overview
In the Customer form, addresses are now saved to the associated User model rather than directly to the Customer model. This makes sense because each Customer has a linked User account, and addresses are typically associated with the user identity.

## Architecture

### **Data Flow**
```
CustomerForm → Customer.user → User.addresses → Address Table
```

### **Model Relationships**
- **Customer** `belongsTo` **User** (via `user_id`)
- **User** `hasMany` **Address** (via `HasAddress` trait)
- **Address** `belongsTo` **User** (polymorphic: `addressable_type`, `addressable_id`)

## Implementation Details

### **1. Customer Model** (`app/Models/Customer.php`)
- **Removed** `HasAddress` trait
- **Kept** `user()` relationship
- Customer data is stored in `customers` table
- No direct address relationship

### **2. User Model** (`app/Models/User.php`)
- **Has** `HasAddress` trait
- Handles all address relationships
- Addresses are stored linked to User

### **3. CustomerForm** (`app/Filament/Resources/Customers/Schemas/CustomerForm.php`)
- Uses `AddressFormSection::forRelation('user', ...)`
- Addresses are managed through the `user` relationship
- Form automatically handles the nested relationship

### **4. AddressFormSection Component**
- New method: `forRelation($relationshipName)`
- Creates repeater with relationship: `"{$relationshipName}.addresses"`
- Supports nested relationships for address management

## Database Storage

### **addresses Table Structure:**
```sql
id                   - Primary key
addressable_type     - 'App\Models\User'  
addressable_id       - User ID (not Customer ID)
type                 - Address type (home, work, billing, etc.)
label                - Custom label
street_address_1     - Street address
street_address_2     - Address line 2
city                 - City
state_province       - State/Province
postal_code          - Postal code
country              - Country
latitude             - GPS latitude
longitude            - GPS longitude
is_primary           - Primary address flag
is_active            - Active status
notes                - Additional notes
created_at           - Created timestamp
updated_at           - Updated timestamp
```

### **Key Point:**
- `addressable_type` = `'App\Models\User'`
- `addressable_id` = User ID (from `customers.user_id`)

## How It Works

### **Creating/Editing Customer with Addresses:**

1. **User opens Customer form**
2. **Fills customer details** (name, email, phone, etc.)
3. **Adds addresses** in the "Customer Addresses" section
4. **Form submission:**
   - Customer data → `customers` table
   - User account is created/updated (via Customer model events)
   - Addresses → `addresses` table linked to User

### **Data Retrieval:**
```php
$customer = Customer::find(1);

// Get customer's user account
$user = $customer->user;

// Get addresses through user relationship
$addresses = $customer->user->addresses;
$primaryAddress = $customer->user->primaryAddress;
$billingAddress = $customer->user->billingAddress;

// Get formatted address
$formatted = $customer->user->getFormattedAddress('billing');
```

### **Alternative Access Pattern:**
```php
$customer = Customer::with('user.addresses')->find(1);

// Now you can access addresses efficiently
$addresses = $customer->user->addresses;
```

## Benefits of This Approach

### **✅ Advantages:**
- **Single Source of Truth** - Addresses belong to the user identity
- **Data Consistency** - One set of addresses per user, regardless of roles
- **Shared Addresses** - If user has multiple roles (customer + landlord), addresses are shared
- **Simplified Queries** - Direct access through user relationship
- **Better Data Integrity** - Addresses follow the user account

### **✅ User Experience:**
- **Consistent Interface** - Same address form as User management
- **Address Reuse** - Addresses entered in Customer form appear in User management
- **Single Maintenance** - User only needs to update addresses in one place

## Code Examples

### **Accessing Customer Addresses:**
```php
// In CustomerController or anywhere
$customer = Customer::find(1);

// Get all addresses
$addresses = $customer->user->addresses;

// Get specific address types
$homeAddress = $customer->user->addressesByType('home')->first();
$billingAddress = $customer->user->billingAddress;

// Add address to customer's user account
$customer->user->addAddress([
    'type' => 'billing',
    'label' => 'Primary Billing',
    'street_address_1' => '123 Business St',
    'city' => 'Manila',
    'country' => 'Philippines',
    'is_primary' => true
]);

// Get formatted address for invoicing
$billingAddressFormatted = $customer->user->getFormattedAddress('billing');
```

### **Eager Loading for Performance:**
```php
// Load customers with their addresses
$customers = Customer::with(['user.addresses'])->get();

// Load customers with only primary addresses
$customers = Customer::with(['user.primaryAddress'])->get();

// Load customers with specific address types
$customers = Customer::with(['user.addresses' => function($query) {
    $query->where('type', 'billing');
}])->get();
```

### **Form Usage in Other Contexts:**
```php
// You can now use the forRelation method for other nested relationships
AddressFormSection::forRelation(
    relationshipName: 'company.owner',  // company -> owner -> addresses
    title: 'Owner Addresses',
    addressTypes: ['home' => 'Home', 'office' => 'Office']
)
```

## Validation and Constraints

### **Automatic Validations:**
- **Required Fields** - Street address and city are required
- **Primary Address Logic** - Only one address can be primary (handled automatically)
- **User Account Creation** - Customer model automatically creates user account if needed

### **Business Rules:**
- When Customer is created → User account is created automatically
- When Customer is updated → User account is linked if not already
- User gets "Renter" role automatically
- Addresses belong to User, not directly to Customer

## Troubleshooting

### **Common Issues:**

#### **1. "Addresses not showing in Customer form"**
- Ensure Customer has linked User account
- Check `customers.user_id` is populated
- Verify User model has `HasAddress` trait

#### **2. "Addresses saving to wrong model"**
- Verify using `AddressFormSection::forRelation('user', ...)`
- Check relationship name is correct in Customer model

#### **3. "Multiple primary addresses"**
- Address model boot method handles this automatically
- If issues persist, check Address model has proper `booted()` method

### **Debugging Queries:**
```php
// Check if customer has user account
$customer = Customer::find(1);
dd($customer->user_id, $customer->user);

// Check addresses are linked to user
$customer = Customer::with('user.addresses')->find(1);
dd($customer->user->addresses);

// Verify address polymorphic relationship
$address = Address::find(1);
dd($address->addressable_type, $address->addressable_id, $address->addressable);
```

## Migration Notes

If you're migrating from direct Customer addresses to User addresses:

### **Data Migration Script Example:**
```php
// Move existing customer addresses to user addresses
foreach (Customer::whereNotNull('user_id')->get() as $customer) {
    if ($customer->user && $customer->addresses()->exists()) {
        foreach ($customer->addresses as $address) {
            $address->update([
                'addressable_type' => 'App\Models\User',
                'addressable_id' => $customer->user_id
            ]);
        }
    }
}
```

This approach provides a more logical and maintainable way to handle customer addresses by storing them with the user identity rather than the customer profile.