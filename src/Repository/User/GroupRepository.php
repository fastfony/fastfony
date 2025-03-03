<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\Group;
use App\Repository\SaveAndRemoveMethodTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }
}
