<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\RoleCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoleCategory>
 */
class RoleCategoryRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleCategory::class);
    }
}
