<?php

declare(strict_types=1);

namespace App\Pro\Entity\Product\Stripe;

use App\Entity\CommonProperties;
use Stripe\StripeClient;

trait Product
{
    use CommonProperties\StripeId;

    public function synchronizeWithStripe(StripeClient $stripe): self
    {
        $productData = [
            'name' => $this->getName(),
            'active' => $this->isEnabled(),
        ];

        if ($this->getDescription()) {
            $productData['description'] = $this->getDescription();
        }

        if ($this->getMarketingFeatures() && 0 < $this->getMarketingFeatures()->count()) {
            $productData['marketing_features'] = [];
            foreach ($this->getMarketingFeatures() as $marketingFeature) {
                $productData['marketing_features'][]['name'] = $marketingFeature->getName();
            }
        }

        if ($this->getStripeId()) {
            // Update existing product
            $stripeProduct = $stripe->products->update($this->getStripeId(), $productData);
        } else {
            // Create new product
            $stripeProduct = $stripe->products->create($productData);
            $this->setStripeId($stripeProduct->id);
        }

        return $this;
    }

    public function removeOnStripe(StripeClient $stripe): self
    {
        if ($this->getStripeId()) {
            // We start by disabled the product on Stripe in case of deletion is not possible
            $stripe->products->update($this->getStripeId(), ['active' => false]);
            // Then we try to delete the product
            $stripe->products->delete($this->getStripeId());
        }

        return $this;
    }
}
