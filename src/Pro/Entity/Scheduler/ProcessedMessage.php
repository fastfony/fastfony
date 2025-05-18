<?php

declare(strict_types=1);

namespace App\Pro\Entity\Scheduler;

use Doctrine\ORM\Mapping as ORM;
use Zenstruck\Messenger\Monitor\History\Model\ProcessedMessage as BaseProcessedMessage;

#[ORM\Entity(readOnly: true)]
class ProcessedMessage extends BaseProcessedMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function id(): ?int
    {
        return $this->id;
    }
}
