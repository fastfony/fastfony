<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties\Required;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Name
{
    #[Assert\NotBlank]
    #[ORM\Column(length: 60)]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
