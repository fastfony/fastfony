<?php

declare(strict_types=1);

namespace App\Controller\Api\Taxonomy;

use App\Repository\TaxonomyRepository;
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
