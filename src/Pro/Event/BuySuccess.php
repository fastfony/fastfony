<?php

declare(strict_types=1);

namespace App\Pro\Event;

use App\Pro\Entity\Product\Price;
use Symfony\Contracts\EventDispatcher\Event;

class BuySuccess extends Event
{
    public function __construct(
        private Price $price,
        private string $stripeSessionId,
    ) {
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getStripeSessionId(): string
    {
        return $this->stripeSessionId;
    }
}
