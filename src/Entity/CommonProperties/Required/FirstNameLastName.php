<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties\Required;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @phpstan-ignore trait.unused */
trait FirstNameLastName
{
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $firstName;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $lastName;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
}
