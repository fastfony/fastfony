<?php

declare(strict_types=1);

namespace App\Pro\Repository\Product;

use App\Pro\Entity\Product\Price;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Price>
 */
class PriceRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }
}
