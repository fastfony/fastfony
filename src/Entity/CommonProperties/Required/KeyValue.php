<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties\Required;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait KeyValue
{
    #[Assert\NotBlank]
    #[ORM\Column(name: '`key`', length: 255)]
    private string $key;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private string $value;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
