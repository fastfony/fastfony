<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Entity\Page\Page;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class Show extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route(
        '/pages/{pageSlug}',
        name: 'page_show',
        requirements: ['slug' => Requirement::ASCII_SLUG],
        methods: ['GET'],
    )]
    #[Template('pages/show.html.twig')]
    public function __invoke(
        #[MapEntity(mapping: ['pageSlug' => 'slug'])]
        Page $page,
    ): array {
        if (!$page->isEnabled() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException();
        }

        return [
            'page' => $page,
        ];
    }
}
