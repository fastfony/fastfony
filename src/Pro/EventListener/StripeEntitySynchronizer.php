<?php

declare(strict_types=1);

namespace App\Pro\EventListener;

use App\Pro\Client\Stripe;
use App\Pro\Entity\Product\Price;
use App\Pro\Entity\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEntityListener(event: Events::prePersist, method: 'synchronizeWithStripe', entity: Product::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'synchronizeWithStripe', entity: Product::class)]
#[AsEntityListener(event: Events::preRemove, method: 'removeOnStripe', entity: Product::class)]
#[AsEntityListener(event: Events::prePersist, method: 'synchronizeWithStripe', entity: Price::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'synchronizeWithStripe', entity: Price::class)]
#[AsEntityListener(event: Events::preRemove, method: 'removeOnStripe', entity: Price::class)]
class StripeEntitySynchronizer
{
    public function __construct(
        private readonly Stripe $stripe,
        private readonly LoggerInterface $logger,
        private readonly RequestStack $requestStack,
        private readonly bool $stripeEnabled,
    ) {
    }

    public function synchronizeWithStripe(object $entity): void
    {
        $this->callStripe('synchronizeWithStripe', $entity);
    }

    public function removeOnStripe(object $entity): void
    {
        $this->callStripe('removeOnStripe', $entity);
    }

    private function callStripe(string $methodName, object $entity): void
    {
        // Check if Stripe is enabled and if entity uses the StripeId trait
        if ($this->stripeEnabled && $this->usesStripeIdTrait($entity)) {
            try {
                $entity->$methodName($this->stripe->getClient());
            } catch (ApiErrorException $e) {
                $message = 'Stripe API error: '.$e->getMessage();
                $this->logger->error(
                    $message,
                    [
                        'entity' => $entity::class,
                        'id' => $entity->getId(),
                    ],
                );

                /* @phpstan-ignore method.notFound */
                $this->requestStack->getSession()->getFlashBag()->add('danger', $message);
            }
        }
    }

    private function usesStripeIdTrait(object $entity): bool
    {
        $reflectionClass = new \ReflectionClass($entity::class);

        return $reflectionClass->hasMethod('getStripeId')
            && $reflectionClass->hasMethod('removeOnStripe')
            && $reflectionClass->hasMethod('synchronizeWithStripe');
    }
}
