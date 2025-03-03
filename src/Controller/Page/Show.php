<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Entity\Page\Page;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/pages/{pageSlug}', name: 'page_show', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET'])]
#[Template('pages/show.html.twig')]
class Show extends AbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['pageSlug' => 'slug'])] Page $page,
    ): array {
        return [
            'page' => $page,
        ];
    }
}
