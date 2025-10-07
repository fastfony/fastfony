<?php

declare(strict_types=1);

namespace App\Repository\Notification;

use App\Entity\Notification\AppChannel;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppChannel>
 */
class AppChannelRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppChannel::class);
    }
}
