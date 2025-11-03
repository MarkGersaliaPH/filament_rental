<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Services\InvoiceGenerationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'customer_id',
        'landlord_id',
        'start_date',
        'end_date',
        'rent_amount',
        'security_deposit',
        'payment_frequency',
        'status',
        'payment_status',
        'contract_file',
        'notes',
        'approved_at',
        'approved_by',
        'terminated_at',
        'termination_reason',
        'next_due_date',
        'total_amount',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_at' => 'timestamp',
        'terminated_at' => 'timestamp',
        'payment_status' => PaymentStatus::class,
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    } 

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class);
    } 
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'billable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', PaymentStatus::Overdue);
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    // Accessors & Mutators
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->payment_status === PaymentStatus::Overdue;
    }

    public function getRemainingBalanceAttribute(): float
    {
        return (float) ($this->rent_amount - $this->total_amount);
    }

    public function getIsApprovedAttribute(): bool
    {
        return !is_null($this->approved_at);
    }

    // in Rental.php
    public function getBuyerAttribute()
    {
        return $this->customer;
    }

    public function getSellerAttribute()
    {
        return $this->landlord;
    }

    /**
     * Generate an invoice for this rental using the InvoiceGenerationService.
     * 
     * @param array $options Additional options for invoice generation
     * @return Invoice
     * @throws \Exception
     */
    public function generateInvoice(array $options = []): Invoice
    {
        return InvoiceGenerationService::generateForRental($this, $options);
    }

    /**
     * Check if this rental has any invoices.
     * 
     * @return bool
     */
    public function hasInvoices(): bool
    {
        return InvoiceGenerationService::modelHasInvoices($this);
    }

    /**
     * Get all invoices for this rental.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInvoices()
    {
        return InvoiceGenerationService::getModelInvoices($this);
    }
}
