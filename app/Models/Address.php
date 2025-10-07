<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'type',
        'label',
        'street_address_1',
        'street_address_2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'is_primary',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $attributes = [
        'country' => 'Philippines',
        'type' => 'general',
        'is_primary' => false,
        'is_active' => true,
    ];

    /**
     * Boot the model.
     */
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

    /**
     * Get the parent addressable model (User, Company, etc.).
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get only active addresses.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only primary addresses.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get addresses by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get formatted address as a string.
     */
    public function getFormattedAddress(string $format = 'full'): string
    {
        switch ($format) {
            case 'short':
                return $this->getShortFormattedAddress();
            case 'single_line':
                return $this->getSingleLineAddress();
            case 'full':
            default:
                return $this->getFullFormattedAddress();
        }
    }

    /**
     * Get full formatted address with line breaks.
     */
    protected function getFullFormattedAddress(): string
    {
        $address = [];
        
        if ($this->label) {
            $address[] = $this->label;
        }
        
        $address[] = $this->street_address_1;
        
        if ($this->street_address_2) {
            $address[] = $this->street_address_2;
        }
        
        $cityStateZip = [];
        $cityStateZip[] = $this->city;
        
        if ($this->state_province) {
            $cityStateZip[] = $this->state_province;
        }
        
        if ($this->postal_code) {
            $cityStateZip[] = $this->postal_code;
        }
        
        if (!empty($cityStateZip)) {
            $address[] = implode(', ', $cityStateZip);
        }
        
        $address[] = $this->country;
        
        return implode("\n", array_filter($address));
    }

    /**
     * Get short formatted address.
     */
    protected function getShortFormattedAddress(): string
    {
        $parts = [$this->city];
        
        if ($this->state_province) {
            $parts[] = $this->state_province;
        }
        
        if ($this->country !== 'Philippines') {
            $parts[] = $this->country;
        }
        
        return implode(', ', array_filter($parts));
    }

    /**
     * Get single line formatted address.
     */
    protected function getSingleLineAddress(): string
    {
        $parts = [];
        
        $parts[] = $this->street_address_1;
        
        if ($this->street_address_2) {
            $parts[] = $this->street_address_2;
        }
        
        $parts[] = $this->city;
        
        if ($this->state_province) {
            $parts[] = $this->state_province;
        }
        
        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }
        
        if ($this->country !== 'Philippines') {
            $parts[] = $this->country;
        }
        
        return implode(', ', array_filter($parts));
    }

    /**
     * Get coordinates as array.
     */
    public function getCoordinates(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ];
        }
        
        return null;
    }

    /**
     * Set coordinates.
     */
    public function setCoordinates(?float $latitude, ?float $longitude): self
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        
        return $this;
    }

    /**
     * Check if address has coordinates.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get display name for the address.
     */
    public function getDisplayName(): string
    {
        if ($this->label) {
            return $this->label . ' (' . $this->type . ')';
        }
        
        return ucfirst($this->type) . ' Address';
    }

    /**
     * Convert address to array for API responses.
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'street_address_1' => $this->street_address_1,
            'street_address_2' => $this->street_address_2,
            'city' => $this->city,
            'state_province' => $this->state_province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'coordinates' => $this->getCoordinates(),
            'is_primary' => $this->is_primary,
            'is_active' => $this->is_active,
            'formatted_address' => $this->getFormattedAddress(),
            'display_name' => $this->getDisplayName(),
        ];
    }
}
