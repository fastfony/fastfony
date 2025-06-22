<?php

declare(strict_types=1);

namespace App\Tests\Unit\Handler;

use App\Client\Stripe;
use App\Entity\Product\Price;
use App\Entity\User\User;
use App\Handler\CheckoutSessionManagement;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\Checkout\SessionService;
use Stripe\StripeClient;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;

#[\AllowDynamicProperties]
class MockStripeClient extends StripeClient
{
}

#[\AllowDynamicProperties]
class MockCheckoutServiceFactory extends CheckoutServiceFactory
{
}

final class CheckoutSessionManagementTest extends TestCase
{
    public function testCreate(): void
    {
        $sessionService = $this->createMock(SessionService::class);
        $sessionService->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(Session::class));

        $checkoutServiceFactory = $this->createMock(MockCheckoutServiceFactory::class);
        $checkoutServiceFactory->sessions = $sessionService;

        $stripeClient = $this->createMock(MockStripeClient::class);
        $stripeClient->checkout = $checkoutServiceFactory;

        $stripe = $this->createMock(Stripe::class);
        $stripe->method('getClient')
            ->willReturn($stripeClient);

        $security = $this->createMock(Security::class);
        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($this->createMock(User::class));

        $checkoutSessionManagement = new CheckoutSessionManagement(
            $this->createMock(RouterInterface::class),
            $security,
            $stripe,
        );

        $checkoutSessionManagement->create(
            $this->createMock(Price::class),
        );
    }
}
