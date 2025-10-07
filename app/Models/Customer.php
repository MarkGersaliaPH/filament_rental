<?php

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Customer extends Model
{
    use HasFactory, SoftDeletes, HasAddress;

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($customer) {
            $customer->createUserAccount();
        });

        static::updated(function ($customer) {
            // If customer doesn't have a user account yet, create one
            if (!$customer->user_id) {
                $customer->createUserAccount();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'is_active',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user account associated with this customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for this customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Create a user account for this customer with Renter role.
     */
    public function createUserAccount(): void
    {
        // Check if customer already has a user account
        if ($this->user_id) {
            return;
        }

        // Check if a user with this email already exists
        $existingUser = User::where('email', $this->email)->first();
        if ($existingUser) {
            // Link existing user to this customer
            $this->update(['user_id' => $existingUser->id]);
            
            // Assign Renter role if not already assigned
            if (!$existingUser->hasRole('Renter')) {
                $existingUser->assignRole('Renter');
            }
            return;
        }

        // Create new user account
        $user = User::create([
            'name' => $this->full_name,
            'email' => $this->email,
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => now(), // Auto-verify email for customers
        ]);

        // Link user to customer
        $this->update(['user_id' => $user->id]);

        // Ensure Renter role exists
        $renterRole = Role::firstOrCreate(
            ['name' => 'Renter'],
            ['description' => 'Customer with rental access to the system']
        );

        // Assign Renter role to user
        $user->assignRole('Renter');
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function rental(){
        return $this->hasOne(Rental::class);
    }
    public function userAddresses()
{
        return $this->hasMany(Address::class, 'addressable_id' );

}
}
