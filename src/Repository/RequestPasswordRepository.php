<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\RequestPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestPassword>
 */
class RequestPasswordRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestPassword::class);
    }
}
