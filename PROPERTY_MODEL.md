# Property Model Documentation

## Overview
The Property model is a comprehensive solution for managing rental property listings in your Laravel Filament rental system. It includes all essential fields and features needed for a modern property rental platform.

## Database Fields

### Basic Information
- `title` - Property title/name
- `description` - Detailed property description
- `type` - Property type (apartment, house, condo, studio, villa, townhouse, duplex, penthouse)
- `status` - Current status (available, rented, maintenance, inactive)
- `slug` - URL-friendly unique identifier

### Property Details
- `bedrooms` - Number of bedrooms
- `bathrooms` - Number of bathrooms
- `area_sqft` - Area in square feet
- `area_sqm` - Area in square meters
- `floor_number` - Floor level
- `total_floors` - Total floors in building
- `built_year` - Year property was built
- `furnished` - Whether property is furnished
- `pet_friendly` - Pet policy
- `smoking_allowed` - Smoking policy

### Location
- `address` - Street address
- `city` - City
- `state_province` - State/Province
- `postal_code` - ZIP/Postal code
- `country` - Country (defaults to US)
- `latitude` / `longitude` - GPS coordinates
- `neighborhood` - Neighborhood name

### Pricing
- `rent_amount` - Rent price
- `rent_period` - Billing period (monthly, weekly, daily)
- `security_deposit` - Required security deposit
- `cleaning_fee` - Cleaning fee
- `additional_fees` - JSON field for extra fees

### Availability
- `available_from` - Available start date
- `available_until` - Available end date
- `minimum_stay_days` - Minimum rental period
- `maximum_stay_days` - Maximum rental period

### Features & Media
- `amenities` - JSON array of amenities
- `appliances` - JSON array of included appliances
- `utilities_included` - JSON array of included utilities
- `images` - JSON array of property images
- `video_url` - Property video URL
- `virtual_tour_url` - Virtual tour URL

### Contact & Management
- `owner_id` - Foreign key to User model
- `contact_name` - Contact person name
- `contact_phone` - Contact phone number
- `contact_email` - Contact email

### SEO & Marketing
- `meta_title` - SEO title
- `meta_description` - SEO description
- `tags` - JSON array of tags
- `is_featured` - Featured property flag
- `view_count` - Number of views

## Model Features

### Constants
- `Property::TYPES` - Available property types
- `Property::STATUSES` - Available status options
- `Property::RENT_PERIODS` - Available rent periods
- `Property::AMENITIES` - Common amenities list
- `Property::APPLIANCES` - Common appliances list

### Relationships
- `owner()` - Belongs to User
- `bookings()` - Has many Booking records
- `reviews()` - Has many Review records

### Query Scopes
- `available()` - Only available properties
- `featured()` - Only featured properties
- `inCity($city)` - Properties in specific city
- `byType($type)` - Properties of specific type
- `priceRange($min, $max)` - Properties within price range
- `withBedrooms($count)` - Properties with minimum bedrooms
- `withBathrooms($count)` - Properties with minimum bathrooms
- `petFriendly()` - Pet-friendly properties
- `furnished()` - Furnished properties

### Accessors
- `full_address` - Complete formatted address
- `formatted_rent` - Formatted rent display ($2,500/monthly)
- `main_image` - First image from images array
- `rating` - Average review rating
- `review_count` - Total number of reviews

### Helper Methods
- `isAvailable()` - Check if property is available
- `incrementViewCount()` - Increment view counter
- `hasAmenity($amenity)` - Check for specific amenity
- `hasAppliance($appliance)` - Check for specific appliance
- `getTotalMonthlyRent()` - Convert rent to monthly equivalent

### Automatic Features
- Auto-generates unique slugs from title
- Updates `last_updated_at` timestamp automatically
- Soft deletes support
- Route model binding uses slug instead of ID

## Factory & Seeder

### PropertyFactory
- Generates realistic property data
- Type-specific rent ranges and bedroom counts
- Random amenities, appliances, and utilities
- Placeholder images using picsum.photos
- State methods: `available()`, `rented()`, `featured()`, `furnished()`, `petFriendly()`

### PropertySeeder
- Creates 48 diverse properties
- 5 featured properties in major cities
- Mix of available, rented, and maintenance status
- Properties with different rent periods (daily, weekly, monthly)

## Usage Examples

### Creating Properties
```php
// Create a new property
Property::create([
    'title' => 'Modern Downtown Apartment',
    'type' => 'apartment',
    'rent_amount' => 2500.00,
    'city' => 'New York',
    'bedrooms' => 2,
    'bathrooms' => 1,
    'amenities' => ['parking', 'gym', 'pool'],
    // ... other fields
]);

// Using factory
Property::factory()->available()->featured()->create();
```

### Querying Properties
```php
// Get available properties in a city
$properties = Property::available()
    ->inCity('New York')
    ->priceRange(1000, 3000)
    ->withBedrooms(2)
    ->get();

// Get featured properties
$featured = Property::featured()->get();

// Search by type
$apartments = Property::byType('apartment')->available()->get();
```

### Working with Property Data
```php
$property = Property::first();

// Get formatted address
echo $property->full_address;

// Check amenities
if ($property->hasAmenity('parking')) {
    echo 'Parking available';
}

// Get monthly rent equivalent
$monthlyRent = $property->getTotalMonthlyRent();

// Increment view count
$property->incrementViewCount();
```

## Database Indexes
The migration includes optimized indexes for:
- Status and type filtering
- Location-based searches
- Price range queries
- Bedroom/bathroom filtering
- Date availability ranges
- Recent listings

## Testing
Run the test command to verify your setup:
```bash
php artisan test:properties
```

This comprehensive Property model provides everything needed for a professional rental property management system, with proper relationships, validation, search capabilities, and performance optimization.