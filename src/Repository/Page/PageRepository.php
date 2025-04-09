<?php

declare(strict_types=1);

namespace App\Repository\Page;

use App\Entity\Page\Page;
use App\Repository\SaveAndRemoveMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethod;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findHomepage(): ?Page
    {
        return $this->createQueryBuilder('page')
            ->where('page.enabled = true')
            ->andWhere('page.homepage = true')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
