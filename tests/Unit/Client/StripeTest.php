<?php

declare(strict_types=1);

namespace App\Tests\Unit\Client;

use App\Client\Stripe;
use PHPUnit\Framework\TestCase;
use Stripe\StripeClient;

final class StripeTest extends TestCase
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
