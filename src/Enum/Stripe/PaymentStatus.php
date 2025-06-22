<?php

declare(strict_types=1);

namespace App\Enum\Stripe;

enum PaymentStatus: string
{
    case NoPaymentRequired = 'no_payment_required';
    case Paid = 'paid';
    case Unpaid = 'unpaid';
}
