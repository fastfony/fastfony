<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Taxonomy;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @extends NestedTreeRepository<Taxonomy>
 */
class TaxonomyRepository extends NestedTreeRepository
{
    use SaveAndRemoveMethod;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Taxonomy::class));
    }
}
