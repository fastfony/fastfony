<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Event;

use App\Pro\Entity\Product\Price;
use App\Pro\Event\BuySuccess;
use PHPUnit\Framework\TestCase;

class BuySuccessTest extends TestCase
{
    public function testGetPrice(): void
    {
        $price = new Price();
        $buySuccess = new BuySuccess($price, 'stripe_session_id');
        $this->assertSame($price, $buySuccess->getPrice());
    }

    public function testGetStripeSessionId(): void
    {
        $price = new Price();
        $buySuccess = new BuySuccess($price, 'stripe_session_id');
        $this->assertSame('stripe_session_id', $buySuccess->getStripeSessionId());
    }
}
