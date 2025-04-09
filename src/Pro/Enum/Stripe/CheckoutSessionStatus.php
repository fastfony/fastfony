<?php

declare(strict_types=1);

namespace App\Pro\Enum\Stripe;

enum CheckoutSessionStatus: string
{
    case Complete = 'complete';
    case Expired = 'expired';
    case Open = 'open';
}
