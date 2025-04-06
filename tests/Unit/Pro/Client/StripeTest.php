<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Client;

use App\Pro\Client\Stripe;
use PHPUnit\Framework\TestCase;
use Stripe\StripeClient;

class StripeTest extends TestCase
{
    public function testGetClient(): void
    {
        $stripe = new Stripe(true, 'test_api');
        $stripeClient = $stripe->getClient();
        $this->assertInstanceOf(StripeClient::class, $stripeClient);
    }

    public function testFailedGetClient(): void
    {
        $stripe = new Stripe(false, 'test_api');
        $this->expectException(\LogicException::class);
        $stripeClient = $stripe->getClient();

        $stripe = new Stripe(true, '');
        $this->expectException(\LogicException::class);
        $stripeClient = $stripe->getClient();
    }
}
