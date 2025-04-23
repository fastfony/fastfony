<?php

declare(strict_types=1);

namespace App\Pro\Controller\Api\Taxonomy;

use App\Pro\Repository\TaxonomyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetCollection extends AbstractController
{
    public function __invoke(
        TaxonomyRepository $taxonomyRepository,
    ): Response {
        return $this->json([
            'entities' => $taxonomyRepository->childrenHierarchy(),
        ]);
    }
}
