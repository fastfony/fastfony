<?php

declare(strict_types=1);

namespace App\Repository\Notification;

use App\Entity\Notification\AppMessage;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppMessage>
 */
class AppMessageRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppMessage::class);
    }
}
