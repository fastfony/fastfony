<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Product\Price;
use App\Entity\User\User;
use App\Enum\Stripe\CheckoutSessionStatus;
use App\Enum\Stripe\PaymentStatus;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    use CommonProperties\Required\AutoGeneratedId;
    use Stripe\Order;
    use TimestampableEntity;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripePaymentIntentId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeSubscriptionId = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private PaymentStatus $stripePaymentStatus;

    #[ORM\Column(enumType: CheckoutSessionStatus::class)]
    private CheckoutSessionStatus $stripeSessionStatus;

    /**
     * @var array <string, mixed>
     */
    #[ORM\Column(type: Types::JSON)]
    private array $stripeCustomerDetails = [];

    #[ORM\Column]
    private ?int $stripeAmountSubtotal = null;

    #[ORM\Column]
    private ?int $stripeAmountTotal = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Price $price = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $customer = null;

    public function getStripePaymentIntentId(): ?string
    {
        return $this->stripePaymentIntentId;
    }

    public function setStripePaymentIntentId(?string $stripePaymentIntentId): static
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;

        return $this;
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    public function setStripeSubscriptionId(?string $stripeSubscriptionId): static
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

        return $this;
    }

    public function getStripePaymentStatus(): PaymentStatus
    {
        return $this->stripePaymentStatus;
    }

    public function setStripePaymentStatus(PaymentStatus $stripePaymentStatus): static
    {
        $this->stripePaymentStatus = $stripePaymentStatus;

        return $this;
    }

    public function getStripeSessionStatus(): CheckoutSessionStatus
    {
        return $this->stripeSessionStatus;
    }

    public function setStripeSessionStatus(CheckoutSessionStatus $stripeSessionStatus): static
    {
        $this->stripeSessionStatus = $stripeSessionStatus;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getStripeCustomerDetails(): array
    {
        return $this->stripeCustomerDetails;
    }

    /**
     * @param array<string, mixed> $stripeCustomerDetails
     *
     * @return $this
     */
    public function setStripeCustomerDetails(array $stripeCustomerDetails): static
    {
        $this->stripeCustomerDetails = $stripeCustomerDetails;

        return $this;
    }

    public function getStripeAmountSubtotal(): ?int
    {
        return $this->stripeAmountSubtotal;
    }

    public function setStripeAmountSubtotal(int $stripeAmountSubtotal): static
    {
        $this->stripeAmountSubtotal = $stripeAmountSubtotal;

        return $this;
    }

    public function getStripeAmountTotal(): ?int
    {
        return $this->stripeAmountTotal;
    }

    public function setStripeAmountTotal(int $stripeAmountTotal): static
    {
        $this->stripeAmountTotal = $stripeAmountTotal;

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(?Price $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
