<?php

declare(strict_types=1);

namespace App\Repository\Collection;

use App\Entity\Collection\RecordCollection;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecordCollection>
 */
class RecordCollectionRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecordCollection::class);
    }
}
