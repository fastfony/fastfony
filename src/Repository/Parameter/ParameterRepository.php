<?php

declare(strict_types=1);

namespace App\Repository\Parameter;

use App\Entity\Parameter\Parameter;
use App\Repository\SaveAndRemoveMethodTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parameter>
 */
class ParameterRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parameter::class);
    }
}
