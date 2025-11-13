<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'slug',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'area_sqm',
        'floor_number',
        'total_floors',
        'built_year',
        'furnished',
        'pet_friendly',
        'smoking_allowed',
        'address',
        'city',
        'state_province',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'neighborhood',
        'rent_amount',
        'rent_period',
        'security_deposit',
        'cleaning_fee',
        'additional_fees',
        'available_from',
        'available_until',
        'minimum_stay_days',
        'maximum_stay_days',
        'amenities',
        'appliances',
        'utilities_included',
        'images',
        'video_url',
        'virtual_tour_url',
        'owner_id',
        'contact_name',
        'contact_phone',
        'contact_email',
        'meta_title',
        'meta_description',
        'tags',
        'is_featured',
        'view_count',
        'last_updated_at',
    ];

    protected $casts = [
        'furnished' => 'boolean',
        'pet_friendly' => 'boolean',
        'smoking_allowed' => 'boolean',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'area_sqft' => 'decimal:2',
        'area_sqm' => 'decimal:2',
        'available_from' => 'date',
        'available_until' => 'date',
        'last_updated_at' => 'datetime',
        'additional_fees' => 'array',
        'amenities' => 'array',
        'appliances' => 'array',
        'utilities_included' => 'array',
        'images' => 'array',
        'tags' => 'array',
    ];

    protected $appends = ['dispay_rent_amount'];

    protected $dates = [
        'available_from',
        'available_until',
        'last_updated_at',
        'deleted_at',
    ];

    // Property Types
    public const TYPES = [
        'apartment' => 'Apartment',
        'house' => 'House',
        'condo' => 'Condominium',
        'studio' => 'Studio',
        'villa' => 'Villa',
        'townhouse' => 'Townhouse',
        'duplex' => 'Duplex',
        'penthouse' => 'Penthouse',
    ];

    // Property Status
    public const STATUSES = [
        'available' => 'Available',
        'rented' => 'Rented',
        'maintenance' => 'Under Maintenance',
        'inactive' => 'Inactive',
    ];

    // Rent Periods
    public const RENT_PERIODS = [
        'monthly' => 'Monthly',
        'weekly' => 'Weekly',
        'daily' => 'Daily',
    ];

    // Common Amenities
    public const AMENITIES = [
        'parking' => 'Parking',
        'gym' => 'Gym/Fitness Center',
        'pool' => 'Swimming Pool',
        'laundry' => 'Laundry Room',
        'elevator' => 'Elevator',
        'balcony' => 'Balcony/Terrace',
        'garden' => 'Garden',
        'security' => '24/7 Security',
        'concierge' => 'Concierge',
        'internet' => 'High-Speed Internet',
        'air_conditioning' => 'Air Conditioning',
        'heating' => 'Central Heating',
        'fireplace' => 'Fireplace',
    ];

    // Common Appliances
    public const APPLIANCES = [
        'refrigerator' => 'Refrigerator',
        'stove' => 'Stove/Oven',
        'microwave' => 'Microwave',
        'dishwasher' => 'Dishwasher',
        'washer' => 'Washing Machine',
        'dryer' => 'Dryer',
        'garbage_disposal' => 'Garbage Disposal',
        'wine_cooler' => 'Wine Cooler',
    ];
 

    // Boot method for auto-generating slugs
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title);
            }
            $property->last_updated_at = now();
        });

        static::updating(function ($property) {
            $property->last_updated_at = now();
        });
    }

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopePriceRange(Builder $query, ?float $min = null, ?float $max = null): Builder
    {
        if ($min) {
            $query->where('rent_amount', '>=', $min);
        }
        if ($max) {
            $query->where('rent_amount', '<=', $max);
        }
        return $query;
    }

    public function scopeWithBedrooms(Builder $query, int $bedrooms): Builder
    {
        return $query->where('bedrooms', '>=', $bedrooms);
    }

    public function scopeWithBathrooms(Builder $query, int $bathrooms): Builder
    {
        return $query->where('bathrooms', '>=', $bathrooms);
    }

    public function scopePetFriendly(Builder $query): Builder
    {
        return $query->where('pet_friendly', true);
    }

    public function scopeFurnished(Builder $query): Builder
    {
        return $query->where('furnished', true);
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state_province,
            $this->postal_code,
            $this->country !== 'US' ? $this->country : null,
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedRentAttribute(): string
    {
        $amount = number_format($this->rent_amount, 2);
        $period = $this->rent_period;
        return "$${amount}/{$period}";
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }

    public function getRatingAttribute(): ?float
    {
        return $this->reviews()->avg('rating');
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    // Helper Methods
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function hasAmenity(string $amenity): bool
    {
        return in_array($amenity, $this->amenities ?? []);
    }

    public function hasAppliance(string $appliance): bool
    {
        return in_array($appliance, $this->appliances ?? []);
    }

    public function getTotalMonthlyRent(): float
    {
        $baseRent = $this->rent_amount;
        
        if ($this->rent_period === 'weekly') {
            $baseRent *= 4.33; // Average weeks per month
        } elseif ($this->rent_period === 'daily') {
            $baseRent *= 30; // Average days per month
        }

        return $baseRent;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function landlord(){
        return $this->belongsTo(Landlord::class,'owner_id');
    }

    
    public function rentals(){
        return $this->hasMany(Rental::class);
    }

    public function moneyFormat($field){ 
        return number_format($this->{$field});
    }

    public function formattedData($field,$format = "Y F d"){
        return Carbon::parse($this->{$field})->format($format);
    }
 
}
