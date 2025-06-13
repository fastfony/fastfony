<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Stripe;

use App\Pro\Entity\Stripe\Order;
use App\Pro\Enum\Stripe\CheckoutSessionStatus;
use App\Pro\Enum\Stripe\PaymentStatus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\Checkout\SessionService;
use Stripe\StripeClient;

class OrderTest extends TestCase
{
    private MockObject $order;
    private MockObject|StripeClient $stripeClient;
    private MockObject|SessionService $sessionService;
    private MockObject|Session $session;

    protected function setUp(): void
    {
        $this->order = $this->getMockForTrait(
            Order::class,
            [],
            '',
            true,
            true,
            true,
            [
                'getStripeId',
                'setStripeId',
                'setStripePaymentIntentId',
                'setStripeSubscriptionId',
                'setStripePaymentStatus',
                'setStripeSessionStatus',
                'setStripeCustomerDetails',
                'setStripeAmountSubtotal',
                'setStripeAmountTotal',
            ]
        );

        $this->sessionService = $this->createMock(SessionService::class);
        $this->stripeClient = $this->createMock(StripeClient::class);
        $this->stripeClient->checkout = $this->createMock(CheckoutServiceFactory::class);
        $this->stripeClient->checkout->sessions = $this->sessionService;

        $this->session = $this->createMock(Session::class);
    }

    public function testSynchronizeWithStripe(): void
    {
        $stripeId = 'cs_test_123456789';
        $sessionData = [
            'id' => $stripeId,
            'payment_intent' => 'pi_123456789',
            'subscription' => 'sub_123456789',
            'payment_status' => 'paid',
            'status' => 'complete',
            'customer_details' => ['email' => 'test@example.com'],
            'amount_subtotal' => 10000,
            'amount_total' => 12000,
        ];

        $this->order->expects($this->once())
            ->method('getStripeId')
            ->willReturn($stripeId);

        $this->session->expects($this->once())
            ->method('toArray')
            ->willReturn($sessionData);

        $this->sessionService->expects($this->once())
            ->method('retrieve')
            ->with($stripeId)
            ->willReturn($this->session);

        $this->order->expects($this->once())
            ->method('setStripeId')
            ->with($sessionData['id'])
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripePaymentIntentId')
            ->with($sessionData['payment_intent'])
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripeSubscriptionId')
            ->with($sessionData['subscription'])
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripePaymentStatus')
            ->with(PaymentStatus::Paid)
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripeSessionStatus')
            ->with(CheckoutSessionStatus::Complete)
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripeCustomerDetails')
            ->with($sessionData['customer_details'])
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripeAmountSubtotal')
            ->with($sessionData['amount_subtotal'])
            ->willReturnSelf();

        $this->order->expects($this->once())
            ->method('setStripeAmountTotal')
            ->with($sessionData['amount_total'])
            ->willReturnSelf();

        /** @phpstan-ignore method.notFound */
        $result = $this->order->synchronizeWithStripe($this->stripeClient);
        $this->assertSame($this->order, $result);
    }

    public function testRemoveOnStripe(): void
    {
        $this->sessionService->expects($this->never())->method($this->anything());

        /** @phpstan-ignore method.notFound */
        $result = $this->order->removeOnStripe($this->stripeClient);

        $this->assertSame($this->order, $result);
    }
}
