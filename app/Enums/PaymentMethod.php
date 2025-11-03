<?php

namespace App\Enums;

enum PaymentMethod: string 
{ 
    case Cash = 'cash';  
    case BankTransfer = 'bank_transfer';
    case CreditCard = 'credit_card';
    case Gcash = 'gcash';
    case Paypal = 'paypal'; 
    case PayMaya = 'paymaya'; 
}
