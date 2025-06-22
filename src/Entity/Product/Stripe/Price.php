<?php

declare(strict_types=1);

namespace App\Entity\Product\Stripe;

use App\Entity\CommonProperties;
use Stripe\StripeClient;

trait Price
{
    use CommonProperties\StripeId;

    public function synchronizeWithStripe(StripeClient $stripe): self
    {
        // Ensure the product has a Stripe ID before creating/updating price
        if (!$this->getProduct()->getStripeId()) {
            $this->getProduct()->synchronizeWithStripe($stripe);
        }

        $priceData = [
            'unit_amount' => (int) ($this->getUnitAmount() * 100), // Stripe requires amounts in cents
            'currency' => $this->getCurrency(),
            'product' => $this->getProduct()->getStripeId(),
            'nickname' => $this->__toString().' - '.$this->getProduct()->getName(),
        ];
        if ($this->getStripeId()) {
            // For prices, Stripe doesn't allow updates to most fields
            // We can only update metadata, nickname, active.
            $stripe->prices->update(
                $this->getStripeId(),
                [
                    'active' => $this->isEnabled(),
                    'nickname' => $this->__toString().' - '.$this->getProduct()->getName(),
                ]
            );
        } else {
            if ($this->isRecurring()) {
                $priceData['recurring'] = [
                    'interval' => $this->getRecurringInterval()->value,
                    'interval_count' => $this->getRecurringIntervalCount(),
                    'trial_period_days' => $this->getRecurringTrialPeriodDays(),
                ];
            }

            // Create new price
            $stripePrice = $stripe->prices->create($priceData);
            $this->setStripeId($stripePrice->id);
        }

        return $this;
    }

    public function removeOnStripe(StripeClient $stripe): self
    {
        if ($this->getStripeId()) {
            // For prices, Stripe doesn't allow remove, only update to inactive
            $stripe->prices->update(
                $this->getStripeId(),
                [
                    'active' => false,
                    'nickname' => '❌',
                ]
            );
        }

        return $this;
    }
}
