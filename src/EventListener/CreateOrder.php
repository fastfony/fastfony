<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Client\Stripe;
use App\Event\BuySuccess;
use App\Handler\OrderManagement;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: BuySuccess::class, method: 'onBuySuccess')]
class CreateOrder
{
    public function __construct(
        private readonly OrderManagement $orderManagement,
        private readonly LoggerInterface $logger,
        private readonly RequestStack $requestStack,
        private readonly Stripe $stripe,
    ) {
    }

    public function onBuySuccess(BuySuccess $event): void
    {
        try {
            $this->orderManagement->create(
                $event->getPrice(),
                $this->stripe->getClient()->checkout->sessions->retrieve($event->getStripeSessionId()),
            );
        } catch (ApiErrorException $exception) {
            $this->logger->error(
                'Stripe API error: '.$exception->getMessage(),
                [
                    'session_id' => $event->getStripeSessionId(),
                ],
            );

            // We notify but continue because the return is OK
            /* @phpstan-ignore method.notFound */
            $this->requestStack
                ->getSession()
                ->getFlashBag()
                ->add('info', 'flash.product.buy.success_with_api_error')
            ;
        }
    }
}
