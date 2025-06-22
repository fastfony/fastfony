<?php

declare(strict_types=1);

namespace App\Entity\Stripe;

use App\Entity\CommonProperties;
use App\Enum\Stripe\CheckoutSessionStatus;
use App\Enum\Stripe\PaymentStatus;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

trait Order
{
    use CommonProperties\StripeId;

    public function synchronizeWithStripe(StripeClient $stripe): self
    {
        $session = $stripe->checkout->sessions->retrieve($this->getStripeId());
        $this->updateFromStripe($session);

        return $this;
    }

    public function removeOnStripe(StripeClient $stripe): self
    {
        // It's not possible to remove a session on Stripe

        return $this;
    }

    public function updateFromStripe(
        Session $session,
    ): self {
        $session = $session->toArray();
        $this->setStripeId($session['id']);
        $this->setStripePaymentIntentId($session['payment_intent']);
        $this->setStripeSubscriptionId($session['subscription']);
        $this->setStripePaymentStatus(PaymentStatus::tryFrom($session['payment_status']));
        $this->setStripeSessionStatus(CheckoutSessionStatus::tryFrom($session['status']));
        $this->setStripeCustomerDetails($session['customer_details']);
        $this->setStripeAmountSubtotal($session['amount_subtotal']);
        $this->setStripeAmountTotal($session['amount_total']);

        return $this;
    }
}
