<?php

declare(strict_types=1);

namespace App\Pro\Handler;

use App\Entity\User\User;
use App\Pro\Client\Stripe;
use App\Pro\Entity\Product\Price;
use Stripe\Checkout\Session;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class CheckoutSessionManagement
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Security $security,
        private readonly Stripe $stripe,
    ) {
    }

    public function create(
        Price $price,
        int $quantity = 1,
    ): Session {
        $params = [
            'mode' => $price->isRecurring() ? 'subscription' : 'payment',
            'line_items' => [
                [
                    'price' => $price->getStripeId(),
                    'quantity' => $quantity,
                ],
            ],
            'success_url' => $this->router->generate(
                'product_buy_success',
                ['id' => $price->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            ).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->router->generate(
                'product_buy_cancel',
                ['id' => $price->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            ),
        ];

        $user = $this->security->getUser();
        if ($user instanceof User) {
            // If we already know the customer on Stripe, we use it
            if ($user->getLastStripeCustomerId()) {
                $params['customer'] = $user->getLastStripeCustomerId();
            } else {
                // Otherwise, we prefill the customer email on Stripe
                $params['customer_email'] = $user->getEmail();
            }
        }

        return $this->stripe->getClient()->checkout->sessions->create($params);
    }
}
