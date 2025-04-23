<?php

declare(strict_types=1);

namespace App\Pro\Repository;

use App\Pro\Entity\Taxonomy;
use App\Repository\SaveAndRemoveMethod;
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
