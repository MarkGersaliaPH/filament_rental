# HasAddress Trait Usage Examples

## Overview
The `HasAddress` trait provides polymorphic address functionality to any model. Simply use the trait in your model to enable address management.

## Setup

### 1. Add the trait to your model:

```php
<?php

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasAddress;
    
    // Your model code...
}
```

## Available Methods

### Relationship Methods
- `addresses()` - Get all addresses for the model
- `primaryAddress()` - Get the primary address
- `addressesByType($type)` - Get addresses by type
- `billingAddress()` - Get billing address
- `shippingAddress()` - Get shipping address
- `mailingAddress()` - Get mailing address

### Management Methods
- `addAddress($data)` - Add a new address
- `updateAddress($id, $data)` - Update an existing address
- `removeAddress($id)` - Remove an address
- `setPrimaryAddress($id)` - Set an address as primary

### Utility Methods
- `hasAddresses()` - Check if model has any addresses
- `hasAddressType($type)` - Check if model has specific address type
- `getFormattedAddress($type)` - Get formatted address string
- `getFullAddress($type)` - Get full address as single line

## Usage Examples

### 1. Adding Addresses

```php
$user = User::find(1);

// Add a billing address
$user->addAddress([
    'type' => 'billing',
    'label' => 'Home',
    'street_address_1' => '123 Main Street',
    'street_address_2' => 'Apt 4B',
    'city' => 'Manila',
    'state_province' => 'Metro Manila',
    'postal_code' => '1000',
    'country' => 'Philippines',
    'is_primary' => true
]);

// Add a shipping address
$user->addAddress([
    'type' => 'shipping',
    'label' => 'Office',
    'street_address_1' => '456 Business Ave',
    'city' => 'Makati',
    'state_province' => 'Metro Manila',
    'postal_code' => '1200',
    'country' => 'Philippines'
]);
```

### 2. Retrieving Addresses

```php
// Get all addresses
$addresses = $user->addresses;

// Get primary address
$primaryAddress = $user->primaryAddress;

// Get billing address
$billingAddress = $user->billingAddress;

// Get addresses by type
$shippingAddresses = $user->addressesByType('shipping');

// Check if user has addresses
if ($user->hasAddresses()) {
    echo "User has addresses";
}

// Check for specific address type
if ($user->hasAddressType('billing')) {
    echo "User has billing address";
}
```

### 3. Formatting Addresses

```php
// Get formatted address (full format with line breaks)
$formatted = $user->getFormattedAddress('billing');
echo $formatted;
// Output:
// Home
// 123 Main Street
// Apt 4B
// Manila, Metro Manila, 1000
// Philippines

// Get single line address
$singleLine = $user->billingAddress->getFormattedAddress('single_line');
echo $singleLine;
// Output: 123 Main Street, Apt 4B, Manila, Metro Manila, 1000

// Get short format
$short = $user->billingAddress->getFormattedAddress('short');
echo $short;
// Output: Manila, Metro Manila
```

### 4. Managing Addresses

```php
// Update an address
$user->updateAddress($addressId, [
    'street_address_1' => '789 New Street',
    'city' => 'Quezon City'
]);

// Set an address as primary
$user->setPrimaryAddress($addressId);

// Remove an address
$user->removeAddress($addressId);
```

### 5. Working with Coordinates

```php
$address = $user->primaryAddress;

// Set coordinates
$address->setCoordinates(14.5995, 120.9842);
$address->save();

// Get coordinates
$coords = $address->getCoordinates();
// Returns: ['lat' => 14.5995, 'lng' => 120.9842]

// Check if has coordinates
if ($address->hasCoordinates()) {
    echo "Address has GPS coordinates";
}
```

## Address Types

Common address types you can use:
- `general` - Default type
- `billing` - For billing purposes
- `shipping` - For shipping/delivery
- `mailing` - For mail correspondence
- `home` - Home address
- `office` - Office address
- `warehouse` - Warehouse location

## Address Fields

Available fields in the Address model:
- `type` - Address type (billing, shipping, etc.)
- `label` - Custom label for the address
- `street_address_1` - Primary street address
- `street_address_2` - Secondary address line (optional)
- `city` - City name
- `state_province` - State or province (optional)
- `postal_code` - Postal/ZIP code (optional)
- `country` - Country (defaults to 'Philippines')
- `latitude` - GPS latitude (optional)
- `longitude` - GPS longitude (optional)
- `is_primary` - Whether this is the primary address
- `is_active` - Whether the address is active
- `notes` - Additional notes (optional)

## Database Queries

### Using Scopes

```php
// Get only active addresses
$activeAddresses = $user->addresses()->active()->get();

// Get only primary addresses
$primaryAddresses = $user->addresses()->primary()->get();

// Get addresses by type using scope
$billingAddresses = $user->addresses()->byType('billing')->get();
```

### Eager Loading

```php
// Load user with all addresses
$user = User::with('addresses')->find(1);

// Load user with only primary address
$user = User::with('primaryAddress')->find(1);

// Load user with specific address type
$user = User::with(['addresses' => function($query) {
    $query->where('type', 'billing');
}])->find(1);
```

## API Responses

```php
// Get address data for API
$addressData = $address->toApiArray();
// Returns formatted array with all address information
```

This provides a complete address management system that can be easily added to any model in your Laravel application.