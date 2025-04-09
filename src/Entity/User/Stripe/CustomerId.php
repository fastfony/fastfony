<?php

declare(strict_types=1);

namespace App\Entity\User\Stripe;

use App\Entity\User\User;
use App\Repository\User\Stripe\CustomerIdRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CustomerIdRepository::class)]
class CustomerId
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'stripeCustomerIds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
