<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\Pro\Client\Stripe;
use App\Pro\Entity\Product\Price;
use App\Pro\Entity\Product\Product;
use App\Pro\EventListener\StripeEntitySynchronizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\Service\PriceService;
use Stripe\Service\ProductService;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

#[\AllowDynamicProperties]
class MockStripeClient extends StripeClient
{
}

class StripeEntitySynchronizerTest extends TestCase
{
    private Stripe|MockObject $stripeClient;

    protected function setUp(): void
    {
        $this->stripeClient = $this->createMock(Stripe::class);
    }

    public function testSynchronizeWithStripe(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $entity = (new Product())->setName('test product');

        $stripe = $this->createMock(MockStripeClient::class);
        $stripe->products = $this->createMock(ProductService::class);
        $stripe->products->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(\Stripe\Product::class));

        $this->stripeClient->expects($this->once())
            ->method('getClient')
            ->willReturn($stripe);

        $listener = new StripeEntitySynchronizer(
            $this->stripeClient,
            $logger,
            $requestStack,
            true,
        );

        $listener->synchronizeWithStripe($entity);
    }

    public function testRemoveOnStripe(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $entity = (new Price())
            ->setUnitAmount(2)
            ->setCurrency('EUR')
            ->setStripeId('abcde')
        ;

        $stripe = $this->createMock(MockStripeClient::class);
        $stripe->prices = $this->createMock(PriceService::class);
        $stripe->prices->expects($this->once())
            ->method('update');

        $this->stripeClient->expects($this->once())
            ->method('getClient')
            ->willReturn($stripe);

        $listener = new StripeEntitySynchronizer(
            $this->stripeClient,
            $logger,
            $requestStack,
            true,
        );
        $listener->removeOnStripe($entity);
    }

    public function testException(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $entity = (new Price())
            ->setUnitAmount(2)
            ->setCurrency('EUR')
            ->setStripeId('abcde')
        ;

        $stripe = $this->createMock(MockStripeClient::class);
        $stripe->prices = $this->createMock(PriceService::class);
        $stripe->prices->expects($this->never())
            ->method('update');

        $this->stripeClient->expects($this->once())
            ->method('getClient')
            ->willThrowException($this->createMock(ApiErrorException::class));

        $logger->expects($this->once())
            ->method('error');

        $requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->createMock(Session::class));

        $listener = new StripeEntitySynchronizer(
            $this->stripeClient,
            $logger,
            $requestStack,
            true,
        );
        $listener->removeOnStripe($entity);
    }

    public function testNoStripeIdTrait(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        // Entity with no StripeId trait
        $entity = new class {
        };

        $listener = new StripeEntitySynchronizer(
            $this->stripeClient,
            $logger,
            $requestStack,
            true,
        );

        $requestStack->expects($this->never())
            ->method('getSession');

        // Should not call the entity methods because the trait is missing
        $listener->synchronizeWithStripe($entity);
        $listener->removeOnStripe($entity);
    }
}
