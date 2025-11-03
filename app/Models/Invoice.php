<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice as InvoicePackage;

class Invoice extends Model
{
    //
    protected $fillable = [
        'billable_id',
        'billable_type',
        'bill_to_id',
        'bill_from_id',
        'invoice_number',
        'amount',
        'invoice_date',
        'due_date',
        'status',
        'payment_status',
        'file_path',
        'invoice_items',
        'name'
    ];

    protected $casts = [
        'invoice_items' => 'array',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_status' => PaymentStatus::class,
    ];

    protected $InvoiceName = [
        Rental::class => 'Rental Invoice',
    ];

    public function billable()
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::saving(function ($invoice) {
            // Automatically calculate amount from invoice_items if items exist
            if ($invoice->invoice_items && is_array($invoice->invoice_items)) {
                $calculatedAmount = collect($invoice->invoice_items)->sum(function ($item) {
                    return ($item['price_per_unit'] ?? 0) * ($item['quantity'] ?? 1);
                });

                // Only update if the calculated amount differs from current amount
                if ($calculatedAmount != $invoice->amount) {
                    $invoice->amount = $calculatedAmount;
                }
            }
        });
    }

    public function billTo()
    {
        return $this->belongsTo(User::class, 'bill_to_id');
    }
    public function billFrom()
    {
        return $this->belongsTo(User::class, 'bill_from_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function generateInvoice($customer, $seller)
    { 
        $invoice = InvoicePackage::make()
            ->buyer($customer)
            ->name($this->name)
            ->status($this->payment_status->value)
            ->seller($seller)
            ->logo('images/logo.png')
            ->filename("public/invoices/" . $this->invoice_number)
            ->currencyCode('PHP');

        // Add items from invoice_items field
        if ($this->invoice_items) {
            foreach ($this->invoice_items as $item) {
                $invoiceItem = InvoiceItem::make($item['name'])
                    ->pricePerUnit($item['price_per_unit'])
                    ->quantity($item['quantity'] ?? 1);
                $invoice->addItem($invoiceItem);
            }
        }

        $disk = "public"; // Ensure 'public' disk is configured in config/filesystems.php
        $invoice->save($disk);

        return $invoice->url();
    }
}
