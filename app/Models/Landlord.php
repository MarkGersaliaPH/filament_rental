<?php

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Landlord extends Model
{
    use HasFactory, SoftDeletes, HasAddress;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'city',
        'state_province',
        'postal_code',
        'country',
        'company_name',
        'business_type',
        'tax_id',
        'bank_name',
        'account_holder_name',
        'is_verified',
        'is_active',
        'verified_at',
        'contact_hours_start',
        'contact_hours_end',
        'preferred_contact_methods',
        'property_manager_name',
        'property_manager_phone',
        'profile_photo',
        'bio',
        'admin_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'contact_hours_start' => 'datetime:H:i',
        'contact_hours_end' => 'datetime:H:i',
        'preferred_contact_methods' => 'array',
        'average_rating' => 'decimal:2',
        'deleted_at' => 'datetime',
        
    ];

    protected $dates = [
        'date_of_birth',
        'verified_at',
        'deleted_at',
    ];

    // Business Types
    public const BUSINESS_TYPES = [
        'individual' => 'Individual',
        'company' => 'Company',
        'llc' => 'LLC',
    ];

    // Contact Methods
    public const CONTACT_METHODS = [
        'email' => 'Email',
        'phone' => 'Phone Call',
        'sms' => 'Text Message',
        'app' => 'In-App Message',
    ];

    // Boot method for auto-creating user accounts
    protected static function boot()
    {
        parent::boot();

        static::created(function ($landlord) {
            $landlord->createUserAccount();
        });

        static::updated(function ($landlord) {
            if (!$landlord->user_id) {
                $landlord->createUserAccount();
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'owner_id');
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
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeByBusinessType(Builder $query, string $type): Builder
    {
        return $query->where('business_type', $type);
    }

    public function scopeWithRating(Builder $query, float $minRating = 4.0): Builder
    {
        return $query->where('average_rating', '>=', $minRating);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?: $this->full_name;
    } 
    public function getActivePropertiesCountAttribute(): int
    {
        return $this->properties()->where('status', 'available')->count();
    }

    public function getRatingStarsAttribute(): string
    {
        $rating = $this->average_rating;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $stars .= '★';
            } elseif ($rating >= $i - 0.5) {
                $stars .= '☆';
            } else {
                $stars .= '☆';
            }
        }
        
        return $stars;
    }

    // Helper Methods
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function hasCompany(): bool
    {
        return !empty($this->company_name);
    }

    public function isSelfManaged(): bool
    {
        return $this->self_managed;
    }

    public function hasPropertyManager(): bool
    {
        return !$this->self_managed && !empty($this->property_manager_name);
    }

    public function preferredContactMethod(string $method): bool
    {
        return in_array($method, $this->preferred_contact_methods ?? []);
    }

    public function updateRating(): void
    {
        $avgRating = $this->reviews()->avg('rating') ?: 0;
        $totalReviews = $this->reviews()->count();
        
        $this->update([
            'average_rating' => round($avgRating, 2),
            'total_reviews' => $totalReviews,
        ]);
    }

    public function updatePropertyCount(): void
    {
        $totalProperties = $this->properties()->count();
        $this->update(['total_properties' => $totalProperties]);
    }

    public function markAsVerified(): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Create a user account for this landlord with Landlord role.
     */
    public function createUserAccount(): void
    {
        if ($this->user_id) {
            return;
        }

        // Check if user with this email already exists
        $existingUser = User::where('email', $this->email)->first();
        if ($existingUser) {
            $this->update(['user_id' => $existingUser->id]);
            
            if (!$existingUser->hasRole('Landlord')) {
                $existingUser->assignRole('Landlord');
            }
            return;
        }

        // Create new user account
        $user = User::create([
            'name' => $this->full_name,
            'email' => $this->email,
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => now(),
        ]);

        $this->update(['user_id' => $user->id]);

        // Ensure Landlord role exists and assign it
        $landlordRole = Role::firstOrCreate(
            ['name' => 'Landlord'],
            ['description' => 'Property owner with rental management access']
        );

        $user->assignRole('Landlord');
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
