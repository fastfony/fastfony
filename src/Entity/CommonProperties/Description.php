<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait Description
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
