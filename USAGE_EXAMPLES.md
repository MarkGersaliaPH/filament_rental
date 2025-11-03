# User Role-Based Address Helper Methods

The User model now includes several helper methods to get addresses based on the user's role:

## Available Methods

### 1. `getRoleBasedFullAddress(string $type = 'primary'): ?string`
Returns the full address from the appropriate profile (Customer for Renters, Landlord for Landlords).

### 2. `getRoleBasedFormattedAddress(string $type = 'primary', string $format = 'full'): ?string`
Returns the formatted address with optional formatting options.

### 3. `getRoleBasedProfile(): Customer|Landlord|null`
Returns the user's profile model based on their primary role.

### 4. `getPrimaryRoleName(): ?string`
Returns the primary role name with priority: Landlord > Renter > Employee > Admin.

## Usage Examples

```php
// Get a user
$user = User::find(1);

// Get full address based on role
$address = $user->getRoleBasedFullAddress();
// Returns Customer address if user is Renter, Landlord address if Landlord

$billingAddress = $user->getRoleBasedFullAddress('billing');
// Get specific address type

// Get formatted address
$formattedAddress = $user->getRoleBasedFormattedAddress('primary', 'single_line');
// Returns single-line formatted address

// Get the user's profile
$profile = $user->getRoleBasedProfile();
if ($profile instanceof \App\Models\Customer) {
    // User is a Renter with Customer profile
    echo "Customer: " . $profile->full_name;
} elseif ($profile instanceof \App\Models\Landlord) {
    // User is a Landlord
    echo "Landlord: " . $profile->full_name;
}

// Get primary role
$primaryRole = $user->getPrimaryRoleName();
echo "Primary role: " . $primaryRole; // e.g., "Landlord", "Renter", etc.
```

## Invoice Integration Example

```php
// In Invoice model, you can now use:
$user = User::find($invoice->bill_to_id);
$address = $user->getRoleBasedFormattedAddress();

// This will automatically get the correct address whether the user
// is a Customer (Renter) or Landlord
```

## Address Types Available
- `'primary'` - Primary address (default)
- `'billing'` - Billing address
- `'shipping'` - Shipping address  
- `'mailing'` - Mailing address

## Format Types Available (for getRoleBasedFormattedAddress)
- `'full'` - Multi-line formatted address (default)
- `'short'` - City, State, Country format
- `'single_line'` - Single line address

## Fallback Behavior
If the user doesn't have a Customer or Landlord profile, the methods will fall back to the user's own address if they have the HasAddress trait implemented.