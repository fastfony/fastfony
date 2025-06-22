<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\Client\Stripe;
use App\Entity\Product\Price;
use App\Event\BuySuccess;
use App\EventListener\CreateOrder;
use App\Handler\OrderManagement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\Checkout\SessionService;
use Stripe\StripeClient;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class CreateOrderTest extends TestCase
{
    private MockObject|OrderManagement $orderManagement;
    private MockObject|LoggerInterface $logger;
    private MockObject|RequestStack $requestStack;
    private MockObject|Stripe $stripe;
    private CreateOrder $createOrder;
    private MockObject|SymfonySession $session;
    private MockObject|FlashBag $flashBag;
    private MockObject|StripeClient $stripeClient;
    private MockObject|SessionService $checkoutService;
    private MockObject|Session $checkoutSession;

    protected function setUp(): void
    {
        $this->orderManagement = $this->createMock(OrderManagement::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->stripe = $this->createMock(Stripe::class);

        $this->session = $this->createMock(SymfonySession::class);
        $this->flashBag = $this->createMock(FlashBag::class);
        $this->stripeClient = $this->createMock(StripeClient::class);
        $this->checkoutService = $this->createMock(SessionService::class);
        $this->checkoutSession = $this->createMock(Session::class);

        $this->requestStack->method('getSession')->willReturn($this->session);
        $this->session->method('getFlashBag')->willReturn($this->flashBag);

        $this->stripe->method('getClient')->willReturn($this->stripeClient);
        $this->stripeClient->checkout = $this->createMock(CheckoutServiceFactory::class);
        $this->stripeClient->checkout->sessions = $this->checkoutService;

        $this->createOrder = new CreateOrder(
            $this->orderManagement,
            $this->logger,
            $this->requestStack,
            $this->stripe
        );
    }

    public function testOnBuySuccessCreatesOrder(): void
    {
        $price = new Price();
        $sessionId = 'cs_test_123456789';

        $event = $this->createMock(BuySuccess::class);
        $event->method('getPrice')->willReturn($price);
        $event->method('getStripeSessionId')->willReturn($sessionId);

        $this->checkoutService->expects($this->once())
            ->method('retrieve')
            ->with($sessionId)
            ->willReturn($this->checkoutSession);

        $this->orderManagement->expects($this->once())
            ->method('create')
            ->with($price, $this->checkoutSession);

        $this->createOrder->onBuySuccess($event);
    }

    public function testOnBuySuccessHandlesApiException(): void
    {
        $price = new Price();
        $sessionId = 'cs_test_123456789';

        $event = $this->createMock(BuySuccess::class);
        $event->method('getPrice')->willReturn($price);
        $event->method('getStripeSessionId')->willReturn($sessionId);

        $exception = $this->createMock(ApiErrorException::class);

        $this->checkoutService->expects($this->once())
            ->method('retrieve')
            ->with($sessionId)
            ->willThrowException($exception);

        $this->logger->expects($this->once())
            ->method('error');

        $this->flashBag->expects($this->once())
            ->method('add')
            ->with('info', 'flash.product.buy.success_with_api_error');

        $this->createOrder->onBuySuccess($event);
    }

    public function testClassHasEventListenerAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(CreateOrder::class);
        $attributes = $reflectionClass->getAttributes(AsEventListener::class);

        $this->assertCount(1, $attributes);
        $attribute = $attributes[0]->newInstance();
        $this->assertEquals(BuySuccess::class, $attribute->event);
        $this->assertEquals('onBuySuccess', $attribute->method);
    }
}
