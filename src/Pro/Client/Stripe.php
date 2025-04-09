<?php

declare(strict_types=1);

namespace App\Pro\Client;

use Stripe\StripeClient;

class Stripe
{
    private ?StripeClient $stripe = null;

    public function __construct(
        bool $stripeEnabled,
        ?string $stripeApiKey = null,
    ) {
        if ($stripeEnabled && !empty($stripeApiKey)) {
            $this->stripe = new StripeClient($stripeApiKey);
        }
    }

    public function getClient(): StripeClient
    {
        if (null === $this->stripe) {
            throw new \LogicException('Stripe client is not available. Have you enable it and set the API key?');
        }

        return $this->stripe;
    }
}
