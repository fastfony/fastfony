<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties\Required;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Enabled
{
    #[Assert\Type(type: 'bool')]
    #[ORM\Column(options: ['default' => false])]
    private bool $enabled = false;

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }
}
