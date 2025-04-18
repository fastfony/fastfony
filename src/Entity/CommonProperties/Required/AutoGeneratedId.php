<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties\Required;

use Doctrine\ORM\Mapping as ORM;

trait AutoGeneratedId
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
