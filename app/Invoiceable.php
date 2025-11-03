<?php

namespace App;

use App\Models\Invoice;

trait Invoiceable
{

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'billable');
    }
 
}
