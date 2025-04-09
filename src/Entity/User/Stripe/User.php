<?php

declare(strict_types=1);

namespace App\Entity\User\Stripe;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait User
{
    /**
     * @var Collection<int, CustomerId>
     */
    #[ORM\OneToMany(
        targetEntity: CustomerId::class,
        mappedBy: 'user',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $stripeCustomerIds;

    public function initStripeUserTrait(): void
    {
        $this->stripeCustomerIds = new ArrayCollection();
    }

    /**
     * @return Collection<int, CustomerId>
     */
    public function getStripeCustomerIds(): Collection
    {
        return $this->stripeCustomerIds;
    }

    public function hasStripeCustomerId(string $id): bool
    {
        foreach ($this->stripeCustomerIds as $stripeCustomerId) {
            if ($stripeCustomerId->getId() === $id) {
                return true;
            }
        }

        return false;
    }

    public function getLastStripeCustomerId(): ?string
    {
        // We sort the stripeCustomerIds by createdAt and updatedAt
        $stripeCustomerIds = $this->stripeCustomerIds->toArray();
        if (empty($stripeCustomerIds)) {
            return null;
        }
        usort($stripeCustomerIds, static function (CustomerId $a, CustomerId $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt() ?: $a->getUpdatedAt() <=> $b->getUpdatedAt();
        });

        return end($stripeCustomerIds)->getId() ?: null;
    }

    public function appendStripeCustomerId(string $stripeCustomerId): static
    {
        foreach ($this->getStripeCustomerIds() as $item) {
            if ($stripeCustomerId === $item->getId()) {
                return $this;
            }
        }
        $stripeCustomerId = (new CustomerId())
            ->setId($stripeCustomerId)
            ->setUser($this);

        return $this->addStripeCustomerId($stripeCustomerId);
    }

    public function addStripeCustomerId(CustomerId $stripeCustomerId): static
    {
        if (!$this->stripeCustomerIds->contains($stripeCustomerId)) {
            $this->stripeCustomerIds->add($stripeCustomerId);
        }

        return $this;
    }

    public function removeStripeCustomerId(CustomerId $stripeCustomerId): static
    {
        if ($this->stripeCustomerIds->removeElement($stripeCustomerId)) {
            // set the owning side to null (unless already changed)
            if ($stripeCustomerId->getUser() === $this) {
                $stripeCustomerId->setUser(null);
            }
        }

        return $this;
    }
}
