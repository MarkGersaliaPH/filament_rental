<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'customer_id',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
        return $query->where('payment_status', 'overdue');
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
        return $this->payment_status === 'overdue';
    }

    public function getRemainingBalanceAttribute(): float
    {
        return (float) ($this->rent_amount - $this->total_amount);
    }

    public function getIsApprovedAttribute(): bool
    {
        return !is_null($this->approved_at);
    }
}
