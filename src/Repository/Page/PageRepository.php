<?php

declare(strict_types=1);

namespace App\Repository\Page;

use App\Entity\Page\Page;
use App\Repository\SaveAndRemoveMethodTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }
}
