<?php

namespace App\Enums;

enum PaymentStatus: string 
{ 
    case Pending = 'pending';
    case Paid = 'paid';
    case Cancelled = 'cancelled'; 
    case UnPaid = 'unpaid'; 
    case Partial = 'partial'; 
    case Overdue = 'Overdue';  
}
