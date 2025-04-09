<?php

declare(strict_types=1);

namespace App\Repository\User\Stripe;

use App\Entity\User\Stripe\CustomerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerId>
 */
class CustomerIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerId::class);
    }
}
