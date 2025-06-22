<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\Product\Price;
use App\Entity\User\User;
use App\Enum\Stripe\CheckoutSessionStatus;
use App\Enum\Stripe\PaymentStatus;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private Order $order;

    protected function setUp(): void
    {
        $this->order = new Order();
    }

    public function testGetSetStripePaymentStatus(): void
    {
        $paymentStatus = PaymentStatus::Paid;
        $result = $this->order->setStripePaymentStatus($paymentStatus);

        $this->assertSame($paymentStatus, $this->order->getStripePaymentStatus());
        $this->assertSame($this->order, $result);
    }

    public function testGetSetStripeSessionStatus(): void
    {
        $sessionStatus = CheckoutSessionStatus::Complete;
        $result = $this->order->setStripeSessionStatus($sessionStatus);

        $this->assertSame($sessionStatus, $this->order->getStripeSessionStatus());
        $this->assertSame($this->order, $result);
    }

    public function testGetSetStripeAmountSubtotal(): void
    {
        $amount = 2500;
        $result = $this->order->setStripeAmountSubtotal($amount);

        $this->assertSame($amount, $this->order->getStripeAmountSubtotal());
        $this->assertSame($this->order, $result);
    }

    public function testGetSetStripeAmountTotal(): void
    {
        $amount = 3000;
        $result = $this->order->setStripeAmountTotal($amount);

        $this->assertSame($amount, $this->order->getStripeAmountTotal());
        $this->assertSame($this->order, $result);
    }

    public function testGetSetPrice(): void
    {
        $price = new Price();
        $result = $this->order->setPrice($price);

        $this->assertSame($price, $this->order->getPrice());
        $this->assertSame($this->order, $result);
    }

    public function testGetSetCustomer(): void
    {
        $customer = $this->createMock(User::class);
        $result = $this->order->setCustomer($customer);

        $this->assertSame($customer, $this->order->getCustomer());
        $this->assertSame($this->order, $result);
    }
}
