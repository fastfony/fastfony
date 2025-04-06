<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties;

use Doctrine\ORM\Mapping as ORM;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

trait StripeId
{
    #[ORM\Column(nullable: true)]
    private ?string $stripeId = null;

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(?string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    /**
     * @throws ApiErrorException
     */
    abstract public function synchronizeWithStripe(StripeClient $stripe): self;

    /**
     * @throws ApiErrorException
     */
    abstract public function removeOnStripe(StripeClient $stripe): self;
}
