<?php

namespace App\Enums;

enum OrderPaymentStatus: string
{
    case PENDING = 'Pending';
    case PAID = 'Paid'; 
    case CANCELED = 'Canceled';
}
