<?php

declare(strict_types=1);

namespace App\Repository\Product;

use App\Entity\Product\MarketingFeature;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MarketingFeature>
 */
class MarketingFeatureRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketingFeature::class);
    }
}
