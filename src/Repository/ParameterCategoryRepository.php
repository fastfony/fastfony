<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ParameterCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParameterCategory>
 */
class ParameterCategoryRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParameterCategory::class);
    }
}
