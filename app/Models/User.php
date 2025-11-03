<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Invoiceable;
use App\Traits\HasAddress;
use Filament\Panel\Concerns\HasAvatars;
use Filament\Panel\Concerns\HasTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
class User extends Authenticatable 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasTenancy, HasAvatars, HasAddress;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the customer profile associated with this user.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the landlord profile associated with this user.
     */
    public function landlord(): HasOne
    {
        return $this->hasOne(Landlord::class);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel && !$this->hasRole($role)) {
            $this->roles()->attach($roleModel->id);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is an employee.
     */
    public function isEmployee(): bool
    {
        return $this->hasRole('Employee');
    }

    /**
     * Check if user is a renter (customer).
     */
    public function isRenter(): bool
    {
        return $this->hasRole('Renter');
    }

    /**
     * Check if user is a landlord (property owner).
     */
    public function isLandlord(): bool
    {
        return $this->hasRole('Landlord');
    }

    /**
     * Get the user's name with email for display purposes.
     */
    public function getNameWithEmailAttribute(): string
    {
        return "{$this->name} ({$this->email})";
    }

    /**
     * Get the user's full address based on their role.
     * Returns address from Customer if user is a Renter,
     * or from Landlord if user is a Landlord.
     * 
     * @param string $type Address type ('primary', 'billing', 'shipping', 'mailing')
     * @return string|null
     */
    public function getRoleBasedFullAddress(string $type = 'primary'): ?string
    {
        // Check if user is a Renter and has a Customer profile
        if ($this->isRenter() && $this->customer) {
            return $this->customer->getFullAddress($type);
        }
        
        // Check if user is a Landlord and has a Landlord profile
        if ($this->isLandlord() && $this->landlord) {
            return $this->landlord->getFullAddress($type);
        }
        
        // Fallback: return user's own address if they have the HasAddress trait
        if (method_exists($this, 'getFullAddress')) {
            return $this->getFullAddress($type);
        }
        
        return null;
    }

    /**
     * Get the user's formatted address based on their role.
     * Returns formatted address from Customer if user is a Renter,
     * or from Landlord if user is a Landlord.
     * 
     * @param string $type Address type ('primary', 'billing', 'shipping', 'mailing')
     * @param string $format Format type ('full', 'short', 'single_line')
     * @return string|null
     */
    public function getRoleBasedFormattedAddress(string $type = 'primary', string $format = 'full'): ?string
    {
        // Check if user is a Renter and has a Customer profile
        if ($this->isRenter() && $this->customer) {
            return $this->customer->getFormattedAddress($type);
        }
        
        // Check if user is a Landlord and has a Landlord profile  
        if ($this->isLandlord() && $this->landlord) {
            return $this->landlord->getFormattedAddress($type);
        }
        
        // Fallback: return user's own address if they have the HasAddress trait
        if (method_exists($this, 'getFormattedAddress')) {
            return $this->getFormattedAddress($type);
        }
        
        return null;
    }

    /**
     * Get the user's profile model based on their primary role.
     * Returns Customer model if user is a Renter,
     * or Landlord model if user is a Landlord.
     * 
     * @return Customer|Landlord|null
     */
    public function getRoleBasedProfile()
    {
        // Check if user is a Renter and has a Customer profile
        if ($this->isRenter() && $this->customer) {
            return $this->customer;
        }
        
        // Check if user is a Landlord and has a Landlord profile
        if ($this->isLandlord() && $this->landlord) {
            return $this->landlord;
        }
        
        return null;
    }

    /**
     * Helper method to get the primary role name for this user.
     * Prioritizes Landlord > Renter > Employee > Admin
     * 
     * @return string|null
     */
    public function getPrimaryRoleName(): ?string
    {
        if ($this->isLandlord()) {
            return 'Landlord';
        }
        
        if ($this->isRenter()) {
            return 'Renter';
        }
        
        if ($this->isEmployee()) {
            return 'Employee';
        }
        
        if ($this->isAdmin()) {
            return 'Admin';
        }
        
        return null;
    }
}
