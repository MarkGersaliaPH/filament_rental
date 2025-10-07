<?php

namespace App\Traits;

use App\Models\Address;

trait HasAddress
{
    /**
     * Get all of the addresses for the model.
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the primary address for the model.
     */
    public function primaryAddress()
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('is_primary', true);
    }

    /**
     * Get addresses by type (billing, shipping, mailing, etc.).
     */
    public function addressesByType(string $type)
    {
        return $this->addresses()->where('type', $type);
    }

    /**
     * Get the billing address.
     */
    public function billingAddress()
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('type', 'billing');
    }

    /**
     * Get the shipping address.
     */
    public function shippingAddress()
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('type', 'shipping');
    }

    /**
     * Get the mailing address.
     */
    public function mailingAddress()
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('type', 'mailing');
    }

    /**
     * Add an address to the model.
     */
    public function addAddress(array $addressData)
    {
        // If this is marked as primary, unset other primary addresses
        if (isset($addressData['is_primary']) && $addressData['is_primary']) {
            $this->addresses()->update(['is_primary' => false]);
        }

        return $this->addresses()->create($addressData);
    }

    /**
     * Update an address by ID.
     */
    public function updateAddress(int $addressId, array $addressData)
    {
        $address = $this->addresses()->find($addressId);
        
        if (!$address) {
            return null;
        }

        // If this is being marked as primary, unset other primary addresses
        if (isset($addressData['is_primary']) && $addressData['is_primary']) {
            $this->addresses()->where('id', '!=', $addressId)->update(['is_primary' => false]);
        }

        $address->update($addressData);
        
        return $address;
    }

    /**
     * Remove an address by ID.
     */
    public function removeAddress(int $addressId)
    {
        return $this->addresses()->where('id', $addressId)->delete();
    }

    /**
     * Set an address as primary.
     */
    public function setPrimaryAddress(int $addressId)
    {
        // First, unset all addresses as non-primary
        $this->addresses()->update(['is_primary' => false]);
        
        // Then set the specified address as primary
        return $this->addresses()->where('id', $addressId)->update(['is_primary' => true]);
    }

    /**
     * Get formatted address string.
     */
    public function getFormattedAddress(string $type = 'primary')
    {
        $address = null;
        
        if ($type === 'primary') {
            $address = $this->primaryAddress;
        } else {
            $address = $this->addressesByType($type)->first();
        }

        return $address ? $address->getFormattedAddress() : null;
    }

    /**
     * Check if the model has any addresses.
     */
    public function hasAddresses()
    {
        return $this->addresses()->exists();
    }

    /**
     * Check if the model has a specific type of address.
     */
    public function hasAddressType(string $type)
    {
        return $this->addresses()->where('type', $type)->exists();
    }

    /**
     * Get the full address as a single line string.
     */
    public function getFullAddress(string $type = 'primary')
    {
        return $this->getFormattedAddress($type);
    }
}